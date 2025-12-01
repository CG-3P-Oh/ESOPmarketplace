(function () {
/**
 * @license almond 0.3.1 Copyright (c) 2011-2014, The Dojo Foundation All Rights Reserved.
 * Available via the MIT or new BSD license.
 * see: http://github.com/jrburke/almond for details
 */
//Going sloppy to avoid 'use strict' string cost, but strict practices should
//be followed.
/*jslint sloppy: true */
/*global setTimeout: false */

var requirejs, require, define;
(function (undef) {
    var main, req, makeMap, handlers,
        defined = {},
        waiting = {},
        config = {},
        defining = {},
        hasOwn = Object.prototype.hasOwnProperty,
        aps = [].slice,
        jsSuffixRegExp = /\.js$/;

    function hasProp(obj, prop) {
        return hasOwn.call(obj, prop);
    }

    /**
     * Given a relative module name, like ./something, normalize it to
     * a real name that can be mapped to a path.
     * @param {String} name the relative name
     * @param {String} baseName a real name that the name arg is relative
     * to.
     * @returns {String} normalized name
     */
    function normalize(name, baseName) {
        var nameParts, nameSegment, mapValue, foundMap, lastIndex,
            foundI, foundStarMap, starI, i, j, part,
            baseParts = baseName && baseName.split("/"),
            map = config.map,
            starMap = (map && map['*']) || {};

        //Adjust any relative paths.
        if (name && name.charAt(0) === ".") {
            //If have a base name, try to normalize against it,
            //otherwise, assume it is a top-level require that will
            //be relative to baseUrl in the end.
            if (baseName) {
                name = name.split('/');
                lastIndex = name.length - 1;

                // Node .js allowance:
                if (config.nodeIdCompat && jsSuffixRegExp.test(name[lastIndex])) {
                    name[lastIndex] = name[lastIndex].replace(jsSuffixRegExp, '');
                }

                //Lop off the last part of baseParts, so that . matches the
                //"directory" and not name of the baseName's module. For instance,
                //baseName of "one/two/three", maps to "one/two/three.js", but we
                //want the directory, "one/two" for this normalization.
                name = baseParts.slice(0, baseParts.length - 1).concat(name);

                //start trimDots
                for (i = 0; i < name.length; i += 1) {
                    part = name[i];
                    if (part === ".") {
                        name.splice(i, 1);
                        i -= 1;
                    } else if (part === "..") {
                        if (i === 1 && (name[2] === '..' || name[0] === '..')) {
                            //End of the line. Keep at least one non-dot
                            //path segment at the front so it can be mapped
                            //correctly to disk. Otherwise, there is likely
                            //no path mapping for a path starting with '..'.
                            //This can still fail, but catches the most reasonable
                            //uses of ..
                            break;
                        } else if (i > 0) {
                            name.splice(i - 1, 2);
                            i -= 2;
                        }
                    }
                }
                //end trimDots

                name = name.join("/");
            } else if (name.indexOf('./') === 0) {
                // No baseName, so this is ID is resolved relative
                // to baseUrl, pull off the leading dot.
                name = name.substring(2);
            }
        }

        //Apply map config if available.
        if ((baseParts || starMap) && map) {
            nameParts = name.split('/');

            for (i = nameParts.length; i > 0; i -= 1) {
                nameSegment = nameParts.slice(0, i).join("/");

                if (baseParts) {
                    //Find the longest baseName segment match in the config.
                    //So, do joins on the biggest to smallest lengths of baseParts.
                    for (j = baseParts.length; j > 0; j -= 1) {
                        mapValue = map[baseParts.slice(0, j).join('/')];

                        //baseName segment has  config, find if it has one for
                        //this name.
                        if (mapValue) {
                            mapValue = mapValue[nameSegment];
                            if (mapValue) {
                                //Match, update name to the new value.
                                foundMap = mapValue;
                                foundI = i;
                                break;
                            }
                        }
                    }
                }

                if (foundMap) {
                    break;
                }

                //Check for a star map match, but just hold on to it,
                //if there is a shorter segment match later in a matching
                //config, then favor over this star map.
                if (!foundStarMap && starMap && starMap[nameSegment]) {
                    foundStarMap = starMap[nameSegment];
                    starI = i;
                }
            }

            if (!foundMap && foundStarMap) {
                foundMap = foundStarMap;
                foundI = starI;
            }

            if (foundMap) {
                nameParts.splice(0, foundI, foundMap);
                name = nameParts.join('/');
            }
        }

        return name;
    }

    function makeRequire(relName, forceSync) {
        return function () {
            //A version of a require function that passes a moduleName
            //value for items that may need to
            //look up paths relative to the moduleName
            var args = aps.call(arguments, 0);

            //If first arg is not require('string'), and there is only
            //one arg, it is the array form without a callback. Insert
            //a null so that the following concat is correct.
            if (typeof args[0] !== 'string' && args.length === 1) {
                args.push(null);
            }
            return req.apply(undef, args.concat([relName, forceSync]));
        };
    }

    function makeNormalize(relName) {
        return function (name) {
            return normalize(name, relName);
        };
    }

    function makeLoad(depName) {
        return function (value) {
            defined[depName] = value;
        };
    }

    function callDep(name) {
        if (hasProp(waiting, name)) {
            var args = waiting[name];
            delete waiting[name];
            defining[name] = true;
            main.apply(undef, args);
        }

        if (!hasProp(defined, name) && !hasProp(defining, name)) {
            throw new Error('No ' + name);
        }
        return defined[name];
    }

    //Turns a plugin!resource to [plugin, resource]
    //with the plugin being undefined if the name
    //did not have a plugin prefix.
    function splitPrefix(name) {
        var prefix,
            index = name ? name.indexOf('!') : -1;
        if (index > -1) {
            prefix = name.substring(0, index);
            name = name.substring(index + 1, name.length);
        }
        return [prefix, name];
    }

    /**
     * Makes a name map, normalizing the name, and using a plugin
     * for normalization if necessary. Grabs a ref to plugin
     * too, as an optimization.
     */
    makeMap = function (name, relName) {
        var plugin,
            parts = splitPrefix(name),
            prefix = parts[0];

        name = parts[1];

        if (prefix) {
            prefix = normalize(prefix, relName);
            plugin = callDep(prefix);
        }

        //Normalize according
        if (prefix) {
            if (plugin && plugin.normalize) {
                name = plugin.normalize(name, makeNormalize(relName));
            } else {
                name = normalize(name, relName);
            }
        } else {
            name = normalize(name, relName);
            parts = splitPrefix(name);
            prefix = parts[0];
            name = parts[1];
            if (prefix) {
                plugin = callDep(prefix);
            }
        }

        //Using ridiculous property names for space reasons
        return {
            f: prefix ? prefix + '!' + name : name, //fullName
            n: name,
            pr: prefix,
            p: plugin
        };
    };

    function makeConfig(name) {
        return function () {
            return (config && config.config && config.config[name]) || {};
        };
    }

    handlers = {
        require: function (name) {
            return makeRequire(name);
        },
        exports: function (name) {
            var e = defined[name];
            if (typeof e !== 'undefined') {
                return e;
            } else {
                return (defined[name] = {});
            }
        },
        module: function (name) {
            return {
                id: name,
                uri: '',
                exports: defined[name],
                config: makeConfig(name)
            };
        }
    };

    main = function (name, deps, callback, relName) {
        var cjsModule, depName, ret, map, i,
            args = [],
            callbackType = typeof callback,
            usingExports;

        //Use name if no relName
        relName = relName || name;

        //Call the callback to define the module, if necessary.
        if (callbackType === 'undefined' || callbackType === 'function') {
            //Pull out the defined dependencies and pass the ordered
            //values to the callback.
            //Default to [require, exports, module] if no deps
            deps = !deps.length && callback.length ? ['require', 'exports', 'module'] : deps;
            for (i = 0; i < deps.length; i += 1) {
                map = makeMap(deps[i], relName);
                depName = map.f;

                //Fast path CommonJS standard dependencies.
                if (depName === "require") {
                    args[i] = handlers.require(name);
                } else if (depName === "exports") {
                    //CommonJS module spec 1.1
                    args[i] = handlers.exports(name);
                    usingExports = true;
                } else if (depName === "module") {
                    //CommonJS module spec 1.1
                    cjsModule = args[i] = handlers.module(name);
                } else if (hasProp(defined, depName) ||
                           hasProp(waiting, depName) ||
                           hasProp(defining, depName)) {
                    args[i] = callDep(depName);
                } else if (map.p) {
                    map.p.load(map.n, makeRequire(relName, true), makeLoad(depName), {});
                    args[i] = defined[depName];
                } else {
                    throw new Error(name + ' missing ' + depName);
                }
            }

            ret = callback ? callback.apply(defined[name], args) : undefined;

            if (name) {
                //If setting exports via "module" is in play,
                //favor that over return value and exports. After that,
                //favor a non-undefined return value over exports use.
                if (cjsModule && cjsModule.exports !== undef &&
                        cjsModule.exports !== defined[name]) {
                    defined[name] = cjsModule.exports;
                } else if (ret !== undef || !usingExports) {
                    //Use the return value from the function.
                    defined[name] = ret;
                }
            }
        } else if (name) {
            //May just be an object definition for the module. Only
            //worry about defining if have a module name.
            defined[name] = callback;
        }
    };

    requirejs = require = req = function (deps, callback, relName, forceSync, alt) {
        if (typeof deps === "string") {
            if (handlers[deps]) {
                //callback in this case is really relName
                return handlers[deps](callback);
            }
            //Just return the module wanted. In this scenario, the
            //deps arg is the module name, and second arg (if passed)
            //is just the relName.
            //Normalize module name, if it contains . or ..
            return callDep(makeMap(deps, callback).f);
        } else if (!deps.splice) {
            //deps is a config object, not an array.
            config = deps;
            if (config.deps) {
                req(config.deps, config.callback);
            }
            if (!callback) {
                return;
            }

            if (callback.splice) {
                //callback is an array, which means it is a dependency list.
                //Adjust args if there are dependencies
                deps = callback;
                callback = relName;
                relName = null;
            } else {
                deps = undef;
            }
        }

        //Support require(['a'])
        callback = callback || function () {};

        //If relName is a function, it is an errback handler,
        //so remove it.
        if (typeof relName === 'function') {
            relName = forceSync;
            forceSync = alt;
        }

        //Simulate async callback;
        if (forceSync) {
            main(undef, deps, callback, relName);
        } else {
            //Using a non-zero value because of concern for what old browsers
            //do, and latest browsers "upgrade" to 4 if lower value is used:
            //http://www.whatwg.org/specs/web-apps/current-work/multipage/timers.html#dom-windowtimers-settimeout:
            //If want a value immediately, use require('id') instead -- something
            //that works in almond on the global level, but not guaranteed and
            //unlikely to work in other AMD implementations.
            setTimeout(function () {
                main(undef, deps, callback, relName);
            }, 4);
        }

        return req;
    };

    /**
     * Just drops the config on the floor, but returns req in case
     * the config return value is used.
     */
    req.config = function (cfg) {
        return req(cfg);
    };

    /**
     * Expose module registry for debugging and tooling
     */
    requirejs._defined = defined;

    define = function (name, deps, callback) {
        if (typeof name !== 'string') {
            throw new Error('See almond README: incorrect module build, no module name');
        }

        //This module may not have dependencies
        if (!deps.splice) {
            //deps is not an array, so probably means
            //an object literal or factory function for
            //the value. Adjust args.
            callback = deps;
            deps = [];
        }

        if (!hasProp(defined, name) && !hasProp(waiting, name)) {
            waiting[name] = [name, deps, callback];
        }
    };

    define.amd = {
        jQuery: true
    };
}());

define("../lib/almond", function(){});

/**
 * Model that represents part information.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'models/partModel',[], function() {
	var model = Backbone.Model.extend( {
		defaults: {
			formContentData: [],
			order: 0,
			type: 'part',
			clean: true,
			title: 'Part Title'
		},

		initialize: function() {
			/*
			 * TODO: For some reason, each part model is being initialized when you add a new part.
			 */
			this.on( 'change:title', this.unclean );
			this.filterFormContentData();
			this.listenTo( this.get( 'formContentData' ), 'change:order', this.sortFormContentData );
			/*
			 * When we remove a field from our field collection, remove it from this part if it exists there.
			 */
			var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:collection' );
			this.listenTo( fieldCollection, 'remove', this.triggerRemove );

			/*
			 * Set a key for part.
			 */
			if ( ! this.get( 'key' ) ) {
				this.set( 'key', Math.random().toString( 36 ).replace( /[^a-z]+/g, '' ).substr( 0, 8 ) );
			}
            // Cast order as a number to avoid string comparison.
            this.set( 'order', Number( this.get( 'order' ) ) );
		},

		unclean: function() {
			this.set( 'clean', false );
		},

		sortFormContentData: function() {
			this.get( 'formContentData' ).sort();
		},

		triggerRemove: function( fieldModel ) {
			if ( _.isArray( this.get( 'formContentData' ) ) ) {
				this.filterFormContentData();
			}
			this.get( 'formContentData' ).trigger( 'remove:field', fieldModel );
		},

		filterFormContentData: function() {
			if ( ! this.get( 'formContentData' ) ) return;

			var formContentData = this.get( 'formContentData' );
			/*
			 * Update our formContentData by running it through our fromContentData filter
			 */
			var formContentLoadFilters = nfRadio.channel( 'formContent' ).request( 'get:loadFilters' );
			/* 
			* Get our second filter, this will be the one with the highest priority after MP Forms.
			*/
			var sortedArray = _.without( formContentLoadFilters, undefined );
			var callback = sortedArray[ 1 ];
			/*
			 * If our formContentData is an empty array, we want to pass the "empty" flag as true so that filters know it's purposefully empty.
			 */
			var empty = ( 0 == formContentData.length ) ? true : false;
			/*
			 * TODO: This is a bandaid fix to prevent forms with layouts and parts from freaking out of layouts & styles are deactivated.
			 * If Layouts is deactivated, it will try to grab the layout data and show the fields on the appropriate parts.
			 */
			if ( 'undefined' == typeof formContentLoadFilters[4] && _.isArray( formContentData ) && 0 != formContentData.length && 'undefined' != typeof formContentData[0].cells ) {
				/*
				 * We need to get our field keys from our layout data.
				 * Layout data looks like:
				 * Rows
				 *   Row
				 *     Cells
				 *       Cell
				 *         Fields
				 *       Cell
				 *         Fields
				 *   Row
				 *     Cells
				 *       Cell
				 *         Fields  
				 */
				var partFields = [];
				var cells = _.pluck( formContentData, 'cells' );
				_.each( cells, function( cell ) {
					var fields = _.flatten( _.pluck( cell, 'fields' ) );
					partFields = _.union( partFields, fields );
				} );

				formContentData = partFields;

				this.set( 'formContentData', formContentData );
			}

			this.set( 'formContentData', callback( formContentData, empty, formContentData ) );
		}

	} );

	return model;
} );

define( 'models/partCollection',[ 'models/partModel' ], function( PartModel ) {
	var collection = Backbone.Collection.extend( {
		model: PartModel,
		currentElement: false,
		comparator: 'order',

		initialize: function( models, options ){
			models = models || [];

			this.on( 'remove', this.afterRemove );
			this.on( 'add', this.afterAdd );
			this.maybeChangeBuilderClass( models.length );
		},

		afterRemove: function( model, collection, options ) {
			this.changeCurrentPart( model, collection, options );
			this.maybeChangeBuilderClass( model, collection, options );
			/*
			 * If our drawer is open, close it.
			 */
			nfRadio.channel( 'app' ).request( 'close:drawer' );
		},

		afterAdd: function( model ) {
			this.setElement( model );
			this.maybeChangeBuilderClass( model );
		},

		
		maybeChangeBuilderClass: function( count, collection, options ) {
			if ( true === count instanceof Backbone.Model ) {
				count = this.length;
			}

			this.changeBuilderClass( 1 < count );
		},

		changeBuilderClass: function( hasParts ) {
			var builderEl = nfRadio.channel( 'app' ).request( 'get:builderEl' );
			if ( hasParts ) {
				jQuery( builderEl ).addClass( 'nf-has-parts' );
			} else {
				jQuery( builderEl ).removeClass( 'nf-has-parts' );
			}
		},

		changeCurrentPart: function( model, collection, options ) {
			/*
			 * When we remove the current part, change the current part in our collection.
			 *
			 * TODO: Find a way to pass index to has previous or has next for proper testing.
			 * Since the model has been removed, it will always return a -1.
			 */
			if ( this.getElement() == model ) {
				if ( 0 == options.index ) {
					this.setElement( this.at( 0 ) );
				} else {
					this.setElement( this.at( options.index - 1 ) );
				}
			} else if ( 1 == this.length ) {
				this.setElement( this.at( 0 ) );
			}
		},

		getElement: function() {
			/*
			 * If we haven't set an element yet, set it to the first one.
			 */
			if ( ! this.currentElement ) {
				this.setElement( this.at( 0 ), true );
			}
			return this.currentElement;
		},
		  
		setElement: function( model, silent ) {
			if ( model == this.currentElement ) return;
			silent = silent || false;
			this.previousElement = this.currentElement;
			this.currentElement = model;
			if ( ! silent ) {
				/*
				 * If we are editing a part and we change parts, update the data being displayed in the drawer to match the new part.
				 */
				var currentDrawer = nfRadio.channel( 'app' ).request( 'get:currentDrawer' );
				if ( currentDrawer && 'editSettings' == currentDrawer.get( 'id' ) ) {
					var settingGroupCollection = nfRadio.channel( 'mp' ).request( 'get:settingGroupCollection' );
					nfRadio.channel( 'app' ).request( 'open:drawer', 'editSettings', { model: model, groupCollection: settingGroupCollection } );
				}

				this.trigger( 'change:part', this );
			}
		},
		
		next: function (){
			/*
			 * If this isn't the last part, move forward.
			 */
			if ( this.hasNext() ) {
				this.setElement( this.at( this.indexOf( this.getElement() ) + 1 ) );
			}
			
			return this;
		},

		previous: function() {
			/*
			 * If this isn't the first part, move backward.
			 */
			if ( this.hasPrevious() ) {
				this.setElement( this.at( this.indexOf( this.getElement() ) - 1 ) );	
			}
			
			return this;
		},

		hasNext: function() {
			if ( 0 == this.length ) return false;
			return this.length - 1 != this.indexOf( this.getElement() );
		},

		hasPrevious: function() {
			if ( 0 == this.length ) return false;
			return 0 != this.indexOf( this.getElement() );
		},

		getFormContentData: function() {
			return this.getElement().get( 'formContentData' );
		},

		updateOrder: function() {
			this.each( function( model, index ) {
				model.set( 'order', index );
			} );
			this.sort();
		},

		append: function( model ) {
		    var order = _.max( this.pluck( 'order' ) ) + 1;
		    if( model instanceof Backbone.Model ) {
		        model.set( 'order', order );
		    } else {
		        model.order = order;
		    }
		    return this.add( model );
		}
	} );

	return collection;
} );
/**
 * Holds our part collection.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/data',[ 'models/partCollection' ], function ( PartCollection) {
	var controller = Marionette.Object.extend( {
		layoutsEnabed: false,

		initialize: function() {
			/*
			 * Instantiate our part collection.
			 */
			nfRadio.channel( 'mp' ).reply( 'init:partCollection', this.initPartCollection, this );

			/*
			 * Listen for requests for our part collection.
			 */
			nfRadio.channel( 'mp' ).reply( 'get:collection', this.getCollection, this );

			/*
			 * If we don't have Layout & Styles active, when we add a field to our field collection, collection, trigger an "add:model"
			 */
			var formContentLoadFilters = nfRadio.channel( 'formContent' ).request( 'get:loadFilters' );

			/*
			 * Layout & Styles compatibility
			 * TODO: Super Hacky Bandaid fix for making sure we don't trigger an duplicating a field if Layouts is enabled.
			 * If it is enabled, Layouts handles adding duplicated items.
			 */
			this.layoutsEnabed = ( 'undefined' != typeof formContentLoadFilters[4] ) ? true : false;
			this.listenTo( nfRadio.channel( 'fields' ), 'render:newField', function( fieldModel, action ){
                action = action || '';
                if ( this.layoutsEnabed && 'duplicate' == action ) return false;
				this.addField( fieldModel, action );
			}, this );
			/* END Layout & Styles compatibility */

			this.listenTo( nfRadio.channel( 'fields' ), 'render:duplicateField', this.addField );
		},

		initPartCollection: function( partCollection ) {
			this.collection = partCollection;
		},

		getCollection: function() {
			return this.collection;
		},

		addField: function( fieldModel, action ) {
			if ( this.layoutsEnabed && 'duplicate' == action ) return false;
			this.collection.getFormContentData().trigger( 'add:field', fieldModel );
			if( 1 == this.collection.getFormContentData().length ) {
				this.collection.getFormContentData().trigger( 'reset' );
			}
		}

	});

	return controller;
} );

/**
 * Listen for clicks on our previous and next buttons
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define(	'controllers/clickControls',[],	function () {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'mp' ), 'click:previous', this.clickPrevious );
			this.listenTo( nfRadio.channel( 'mp' ), 'click:next', this.clickNext );
			this.listenTo( nfRadio.channel( 'mp' ), 'click:new', this.clickNew );
			this.listenTo( nfRadio.channel( 'mp' ), 'click:part', this.clickPart );

			this.listenTo( nfRadio.channel( 'setting-name-mp_remove' ), 'click:extra', this.clickRemove );
			this.listenTo( nfRadio.channel( 'setting-name-mp_duplicate' ), 'click:extra', this.clickDuplicate );

		},

		clickPrevious: function( e ) {
			var collection = nfRadio.channel( 'mp' ).request( 'get:collection' );
			collection.previous();
		},

		clickNext: function( e ) {
			var collection = nfRadio.channel( 'mp' ).request( 'get:collection' );
			collection.next();
		},

		clickNew: function( e ) {
			var collection = nfRadio.channel( 'mp' ).request( 'get:collection' );
			var model = collection.append({});
			
			/*
			 * Register our new part to the change manager.
			 */
			// Set our 'clean' status to false so that we get a notice to publish changes
			nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
			// Update our preview
			nfRadio.channel( 'app' ).request( 'update:db' );

			// Add our field addition to our change log.
			var label = {
				object: 'Part',
				label: model.get( 'title' ),
				change: 'Added',
				dashicon: 'plus-alt'
			};

			var data = {
				collection: model.collection
			};

			var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'addPart', model, null, label, data );
		},

		clickPart: function( e, partModel ) {
			if ( partModel == partModel.collection.getElement( partModel ) ) {
				/*
				 * If we are on the active part, open the drawer for that part.
				 */
				var settingGroupCollection = nfRadio.channel( 'mp' ).request( 'get:settingGroupCollection' );
				nfRadio.channel( 'app' ).request( 'open:drawer', 'editSettings', { model: partModel, groupCollection: settingGroupCollection } );
			} else {
				/*
				 * If we aren't on the active part, move to it.
				 */
				partModel.collection.setElement( partModel );
			}
		},

		clickRemove: function( e, settingModel, partModel, settingView ) {
			/*
			 * Register our change.
			 */
			// Set our 'clean' status to false so that we get a notice to publish changes
			nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
			// Update our preview
			nfRadio.channel( 'app' ).request( 'update:db' );

			// Add our field addition to our change log.
			var label = {
				object: 'Part',
				label: partModel.get( 'title' ),
				change: 'Removed',
				dashicon: 'dismiss'
			};

			var data = {
				collection: partModel.collection
			};
            
            /**
             * Collect the field models on our part and trash them.
             */
            this.trash = [];
            this.removeFields(partModel.get('formContentData').models, this);
            this.trash.forEach( function( model ) {
                model.collection.remove( model );
            } );

			var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'removePart', partModel, null, label, data );
			/*
			 * Remove our part.
			 */
			partModel.collection.remove( partModel );
		},
        
        removeFields: function( collection, that ) {
            _.each( collection, function( model ) {
                if ( 'undefined' != typeof model ) {
                    if ( 'undefined' != typeof model.get( 'fields' ) ) {
                        that.removeFields( model.get( 'fields' ).models, that );
                    } else if ( 'undefined' != typeof model.get( 'cells' ) ) {
                        that.removeFields( model.get( 'cells' ).models, that );
                    } else if ( 'undefined' != model.get( 'id' ) ) {
                        that.trash.push( model );
                    }
                }
            });
        },

		clickDuplicate: function( e, settingModel, partModel, settingView ) {
			var partClone = nfRadio.channel( 'app' ).request( 'clone:modelDeep', partModel );

            partClone.set( 'key', Math.random().toString( 36 ).replace( /[^a-z]+/g, '' ).substr( 0, 8 ) );
            
            //////////////////////
            
            var duplicatedFields = [];
            var formContentLoadFilters = nfRadio.channel( 'formContent' ).request( 'get:loadFilters' );
            var currentDomain = nfRadio.channel( 'app' ).request( 'get:currentDomain' );
            var currentDomainID = currentDomain.get( 'id' );
            
            // If Layout and Styles is enabled...
            if( 'undefined' != typeof formContentLoadFilters[4] ) {
                _.each( partClone.get( 'formContentData' ).models, function( row, rowIndex ) {
                    duplicatedFields[ rowIndex ] = [];
                    _.each( row.get( 'cells' ).models, function( cell, cellIndex ) {
                        duplicatedFields[ rowIndex ][ cellIndex ] = [];
                        _.each( cell.get( 'fields' ).models, function( field, fieldIndex ) {
                            var newField = nfRadio.channel( 'app' ).request( 'clone:modelDeep', field );

                            // Update our ID to the new tmp id.
                            var tmpID = nfRadio.channel( currentDomainID ).request( 'get:tmpID' );
                            newField.set( 'id', tmpID );
                            // Add new model.
                            duplicatedFields[ rowIndex ][ cellIndex ][ fieldIndex ] = nfRadio.channel( currentDomainID ).request( 'add', newField, true, false, 'duplicate' );
                        } );
                    } );
                } );
                for(var i = 0; i < duplicatedFields.length; i++) {
                    for(var ii = 0; ii < duplicatedFields[i].length; ii++) {
                            partClone.get('formContentData').models[i].get('cells').models[ii].get('fields').models = duplicatedFields[i][ii];
                    }
                }
            }
            // Otherwise (Layout and Styles is not enabled)...
            else {
                _.each( partClone.get( 'formContentData' ).models, function( model, index ) {
                    // Leverage core's Add/Duplicate to generate a new field key.
                    nfRadio.channel( currentDomainID ).request( 'add', /* model */ model, /* silent */ false, /* renderTrigger */ false, /* action */ 'duplicate' );
                });
            }
            
            ///////////////////////
            
			partModel.collection.add( partClone );
			partClone.set( 'order', partModel.get( 'order' ) );
			partModel.collection.updateOrder();
			partModel.collection.setElement( partClone );

			// Set our 'clean' status to false so that we get a notice to publish changes
			nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
			// Update our preview
			nfRadio.channel( 'app' ).request( 'update:db' );

			// Add our field addition to our change log.
			var label = {
				object: 'Part',
				label: partClone.get( 'title' ),
				change: 'Duplicated',
				dashicon: 'admin-page'
			};

			var data = {
				collection: partClone.collection
			};

			var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'duplicatePart', partClone, null, label, data );
		}

	});

	return controller;
} );

/**
 * Listen for drag events on our arrows.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define(	'controllers/gutterDroppables',[],	function () {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'mp' ), 'over:gutter', this.over );
			this.listenTo( nfRadio.channel( 'mp' ), 'out:gutter', this.out );
			this.listenTo( nfRadio.channel( 'mp' ), 'drop:rightGutter', this.dropRight );
			this.listenTo( nfRadio.channel( 'mp' ), 'drop:leftGutter', this.dropLeft );
		},

		over: function( ui, partCollection ) {
			/*
			 * Remove any other draggable placeholders.
			 */
			jQuery( '#nf-main' ).find( '.nf-fields-sortable-placeholder' ).addClass( 'nf-sortable-removed' ).removeClass( 'nf-fields-sortable-placeholder' );

			// Trigger Ninja Forms default handler for being over a field sortable.
			ui.item = ui.draggable;
			nfRadio.channel( 'app' ).request( 'over:fieldsSortable', ui );
			
			/*
			 * If we hover over our droppable for more than x seconds, change the part.
			 */
			// setTimeout( this.changePart, 1500, ui, partCollection );
		},

		out: function( ui, partCollection ) {
			/*
			 * Re-add any draggable placeholders that we removed.
			 */
			jQuery( '#nf-main' ).find( '.nf-sortable-removed' ).addClass( 'nf-fields-sortable-placeholder' );
			
			// Trigger Ninja Forms default handler for being out of a field sortable.
			ui.item = ui.draggable;
			nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );

			/*
			 * If we hover over our droppable for more than x seconds, change the part.
			 */
			// clearTimeout( this.changePart );
		},

		drop: function( ui, partCollection, dir ) {
			ui.draggable.dropping = true;
			ui.item = ui.draggable;
			nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );
			nfRadio.channel( 'fields' ).request( 'sort:fields', null, null, false );

			/*
			 * If we hover over our droppable for more than x seconds, change the part.
			 */
			// clearTimeout( this.changePart );				
		},

		dropLeft: function( ui, partCollection ) {
			this.drop( ui, partCollection, 'left' );
			/*
			 * Check to see if we have a previous part.
			 */
			if ( ! partCollection.hasPrevious() ) return;
			/*
			 * If we're dealing with a field that already exists on our form, handle moving it.
			 */
			if ( jQuery( ui.draggable ).hasClass( 'nf-field-wrap' ) ) {
				var fieldModel = nfRadio.channel( 'fields' ).request( 'get:field', jQuery( ui.draggable ).data( 'id' ) );
				/*
				 * Add the dragged field to the previous part.
				 */
				var oldOrder = fieldModel.get( 'order' );

				partCollection.getFormContentData().trigger( 'remove:field', fieldModel );
				var previousPart = partCollection.at( partCollection.indexOf( partCollection.getElement() ) - 1 );
				previousPart.get( 'formContentData' ).trigger( 'append:field', fieldModel );
				
				/*
				 * Register our part change to the change manager.
				 */
				// Set our 'clean' status to false so that we get a notice to publish changes
				nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
				// Update our preview
				nfRadio.channel( 'app' ).request( 'update:db' );

				// Add our field addition to our change log.
				var label = {
					object: 'Field',
					label: fieldModel.get( 'label' ),
					change: 'Changed Part',
					dashicon: 'image-flip-horizontal'
				};

				var data = {
					oldPart: partCollection.getElement(),
					newPart: previousPart,
					fieldModel: fieldModel,
					oldOrder: oldOrder
				};

				var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'fieldChangePart', previousPart, null, label, data );

			} else if ( jQuery( ui.draggable ).hasClass( 'nf-field-type-draggable' ) ) {
				var type = jQuery( ui.draggable ).data( 'id' );
				var fieldModel = this.addField( type, partCollection );
				/*
				 * We have a previous part. Add the new field to it.
				 */
				partCollection.at( partCollection.indexOf( partCollection.getElement() ) - 1 ).get( 'formContentData' ).trigger( 'append:field', fieldModel );
			} else {
				/*
				 * We are dropping a stage
				 */
				
				// Make sure that our staged fields are sorted properly.	
				nfRadio.channel( 'fields' ).request( 'sort:staging' );
				// Grab our staged fields.
				var stagedFields = nfRadio.channel( 'fields' ).request( 'get:staging' );

				_.each( stagedFields.models, function( field, index ) {
					// Add our field.
					
					var fieldModel = this.addField( field.get( 'slug' ), partCollection );
					partCollection.at( partCollection.indexOf( partCollection.getElement() ) - 1 ).get( 'formContentData' ).trigger( 'append:field', fieldModel );
				}, this );

				// Clear our staging
				nfRadio.channel( 'fields' ).request( 'clear:staging' );
			}
		},

		dropRight: function( ui, partCollection ) {
			this.drop( ui, partCollection );
			/*
			 * If we're dealing with a field that already exists on our form, handle moving it.
			 */
			if ( jQuery( ui.draggable ).hasClass( 'nf-field-wrap' ) ) {
				var fieldModel = nfRadio.channel( 'fields' ).request( 'get:field', jQuery( ui.draggable ).data( 'id' ) );
				/*
				 * Check to see if we have a next part.
				 */
				if ( partCollection.hasNext() ) {
					/*
					 * Add the dragged field to the next part.
					 */
					var oldOrder = fieldModel.get( 'order' );

					partCollection.getFormContentData().trigger( 'remove:field', fieldModel );
					var nextPart = partCollection.at( partCollection.indexOf( partCollection.getElement() ) + 1 );
					nextPart.get( 'formContentData' ).trigger( 'append:field', fieldModel );
				
					/*
					 * Register our part change to the change manager.
					 */
					// Set our 'clean' status to false so that we get a notice to publish changes
					nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
					// Update our preview
					nfRadio.channel( 'app' ).request( 'update:db' );

					// Add our field addition to our change log.
					var label = {
						object: 'Field',
						label: fieldModel.get( 'label' ),
						change: 'Changed Part',
						dashicon: 'image-flip-horizontal'
					};

					var data = {
						oldPart: partCollection.getElement(),
						newPart: nextPart,
						fieldModel: fieldModel,
						oldOrder: oldOrder
					};

					var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'fieldChangePart', nextPart, null, label, data );

				} else {
					var oldPart = partCollection.getElement();
					/*
					 * Add the dragged field to a new part.
					 */
					partCollection.getFormContentData().trigger( 'remove:field', fieldModel );
					var newPart = partCollection.append( { formContentData: [ fieldModel.get( 'key' ) ] } );
					partCollection.setElement( newPart );

					/*
					 * Register our new part to the change manager.
					 */
					// Set our 'clean' status to false so that we get a notice to publish changes
					nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
					// Update our preview
					nfRadio.channel( 'app' ).request( 'update:db' );

					// Add our field addition to our change log.
					var label = {
						object: 'Part',
						label: newPart.get( 'title' ),
						change: 'Added',
						dashicon: 'plus-alt'
					};

					var data = {
						collection: newPart.collection,
						oldPart: oldPart,
						fieldModel: fieldModel
					};

					var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'addPart', newPart, null, label, data );

				}
			} else if ( jQuery( ui.draggable ).hasClass( 'nf-field-type-draggable' ) ) {
				var type = jQuery( ui.draggable ).data( 'id' );
				var fieldModel = this.addField( type, partCollection );
				if ( partCollection.hasNext() ) {
					/*
					 * We have a next part. Add the new field to it.
					 */
					partCollection.at( partCollection.indexOf( partCollection.getElement() ) + 1 ).get( 'formContentData' ).trigger( 'append:field', fieldModel );
					return false;
				} else {
					/*
					 * We don't have a next part, so add a new one, then add this field to it.
					 */
					var newPart = partCollection.append( { formContentData: [ fieldModel.get( 'key' ) ] } );
					partCollection.setElement( newPart );

					/*
					 * Register our new part to the change manager.
					 */
					// Set our 'clean' status to false so that we get a notice to publish changes
					nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
					// Update our preview
					nfRadio.channel( 'app' ).request( 'update:db' );

					// Add our field addition to our change log.
					var label = {
						object: 'Part',
						label: newPart.get( 'title' ),
						change: 'Added',
						dashicon: 'plus-alt'
					};

					var data = {
						collection: newPart.collection,

					};

					var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'addPart', newPart, null, label, data );

					return newPart;
				}
			} else {
				// Make sure that our staged fields are sorted properly.	
				nfRadio.channel( 'fields' ).request( 'sort:staging' );
				// Grab our staged fields.
				var stagedFields = nfRadio.channel( 'fields' ).request( 'get:staging' );
				
				var keys = [];
				_.each( stagedFields.models, function( field, index ) {
					// Add our field.
					var fieldModel = this.addField( field.get( 'slug' ), partCollection );
					if ( partCollection.hasNext() ) {
						partCollection.at( partCollection.indexOf( partCollection.getElement() ) + 1 ).get( 'formContentData' ).trigger( 'append:field', fieldModel );
					} else {
						keys.push( fieldModel.get( 'key' ) );
					}
					
				}, this );

				if ( ! partCollection.hasNext() ) {
					/*
					 * Add each of our fields to our next part
					 */
					var newPart = partCollection.append( { formContentData: keys } );
					partCollection.setElement( newPart );

					/*
					 * Register our new part to the change manager.
					 */
					// Set our 'clean' status to false so that we get a notice to publish changes
					nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
					// Update our preview
					nfRadio.channel( 'app' ).request( 'update:db' );

					// Add our field addition to our change log.
					var label = {
						object: 'Part',
						label: newPart.get( 'title' ),
						change: 'Added',
						dashicon: 'plus-alt'
					};

					var data = {
						collection: newPart.collection
					};

					var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'addPart', newPart, null, label, data );				
				}

				// Clear our staging
				nfRadio.channel( 'fields' ).request( 'clear:staging' );
			}
		},

		addField: function( type, collection ) {
			var fieldType = nfRadio.channel( 'fields' ).request( 'get:type', type ); 
			// Add our field
			var fieldModel = nfRadio.channel( 'fields' ).request( 'add', {
				label: fieldType.get( 'nicename' ),
				type: type
			} );

			collection.getFormContentData().trigger( 'remove:field', fieldModel );
			return fieldModel;
		},

		changePart: function( ui, partCollection ) {
			partCollection.next();
			jQuery( ui.helper ).draggable();
		}

	});

	return controller;
} );
/**
 * Stores part setting information.
 *
 * @package Ninja Forms builder
 * @subpackage App - Edit Settings Drawer
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/partSettings',[], function( SettingGroupCollection ) {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			/*
			 * Instantiate our setting group collection
			 */
			this.setupCollection();

			// Respond to requests for our part setting group collection
			nfRadio.channel( 'mp' ).reply( 'get:settingGroupCollection', this.getCollection, this );
		},

		setupCollection: function() {
			var settingGroupCollection = nfRadio.channel( 'app' ).request( 'get:settingGroupCollectionDefinition' );
			this.collection = new settingGroupCollection([
				{
					id: 'primary',
					label: '',
					display: true,
					priority: 100,
					settings: [
						{
							name: 'title',
							type: 'textbox',
							label: 'Part Title',
							width: 'full',
						},
						{
							name: 'mp_remove',
							type: 'html',
							width: 'one-half',
							value: '<a href="#" class="nf-remove-part nf-button secondary extra">Remove Part</a>'
						}
					]
				},
			] );
			// only allow part duplication if Layouts & Styles exist
			var formContentLoadFilters = nfRadio.channel( 'formContent'  ).request( 'get:loadFilters' );
			if( 'undefined' != typeof formContentLoadFilters[4] ) {
				 var colSettings  = this.collection.models[0].get( 'settings' );
					 colSettings.push(
					{
						name: 'mp_duplicate',
						type: 'html',
						width: 'one-half',
						value: '<a href="#" class="nf-duplicate-part nf-button secondary extra">Duplicate Part</a>'
					}
				)
			}
		},

		/**
		 * Return our setting group collection.
		 *
		 * @since  3.0
		 * @return backbone.collection
		 */
		getCollection: function() {
			return this.collection;
		}

	});

	return controller;
} );

/**
 * Handles events for the part items in our bottom drawer.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define(	'controllers/partDroppable',[],	function () {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'mp' ), 'over:part', this.over );
			this.listenTo( nfRadio.channel( 'mp' ), 'out:part', this.out );
			this.listenTo( nfRadio.channel( 'mp' ), 'drop:part', this.drop );
		},

		over: function( e, ui, partModel, partView ) {
			/*
			 * Remove any other draggable placeholders.
			 */
			jQuery( '#nf-main' ).find( '.nf-fields-sortable-placeholder' ).addClass( 'nf-sortable-removed' ).removeClass( 'nf-fields-sortable-placeholder' );

			// Trigger Ninja Forms default handler for being over a field sortable.
			ui.item = ui.draggable;

			if ( jQuery( ui.draggable ).hasClass( 'nf-field-type-draggable' ) || jQuery( ui.draggable ).hasClass( 'nf-stage' ) ) {
				nfRadio.channel( 'app' ).request( 'over:fieldsSortable', ui );
			} else {
				jQuery( ui.helper ).css( { 'width': '300px', 'height': '50px', 'opacity': '0.7' } );
			}
		},

		out: function( e, ui, partModel, partView ) {
			/*
			 * Re-add any draggable placeholders that we removed.
			 */
			jQuery( '#nf-main' ).find( '.nf-sortable-removed' ).addClass( 'nf-fields-sortable-placeholder' );

			// Trigger Ninja Forms default handler for being out of a field sortable.
			ui.item = ui.draggable;
			if ( jQuery( ui.draggable ).hasClass( 'nf-field-type-draggable' ) || jQuery( ui.draggable ).hasClass( 'nf-stage' ) ) {
				nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );
			} else {
				// Get our sortable element.
				var sortableEl = nfRadio.channel( 'fields' ).request( 'get:sortableEl' );
				// Get our fieldwidth.
				var fieldWidth = jQuery( sortableEl ).width();
				var fieldHeight = jQuery( sortableEl ).height();

				jQuery( ui.helper ).css( { 'width': fieldWidth, 'height': '', 'opacity': '' } );
			}
		},

		drop: function( e, ui, partModel, partView ) {
			ui.draggable.dropping = true;
			// Trigger Ninja Forms default handler for being out of a field sortable.
			ui.item = ui.draggable;
			nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );

			jQuery( ui.draggable ).effect( 'transfer', { to: jQuery( partView.el ) }, 500 );

			if ( jQuery( ui.draggable ).hasClass( 'nf-field-wrap' ) ) { // Dropping a field that already exists
				this.dropField( e, ui, partModel, partView );
			} else if ( jQuery( ui.draggable ).hasClass( 'nf-field-type-draggable' ) ) { // Dropping a new field
				this.dropNewField( e, ui, partModel, partView );
			} else if ( jQuery( ui.draggable ).hasClass( 'nf-stage' ) ) { // Dropping the staging area
				this.dropStaging( e, ui, partModel, partView );
			}
		},

		dropField: function( e, ui, partModel, partView ) {
			/*
			 * If we are dropping a field that exists on our form already:
			 * Remove it from the current part.
			 * Add it to the new part.
			 */
			nfRadio.channel( 'fields' ).request( 'sort:fields', null, null, false );
			nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );
			var fieldModel = nfRadio.channel( 'fields' ).request( 'get:field', jQuery( ui.draggable ).data( 'id' ) );
			var oldOrder = fieldModel.get( 'order' );
			var oldPart = partModel.collection.getElement();
			var newPart = partModel;

			/*
			 * Add the dragged field to the previous part.
			 */
			partModel.collection.getFormContentData().trigger( 'remove:field', fieldModel );
			partModel.get( 'formContentData' ).trigger( 'append:field', fieldModel );

			/*
			 * Register our part change to the change manager.
			 */
			// Set our 'clean' status to false so that we get a notice to publish changes
			nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
			// Update our preview
			nfRadio.channel( 'app' ).request( 'update:db' );

			// Add our field addition to our change log.
			var label = {
				object: 'Field',
				label: fieldModel.get( 'label' ),
				change: 'Changed Part',
				dashicon: 'image-flip-horizontal'
			};

			var data = {
				oldPart: oldPart,
				newPart: newPart,
				fieldModel: fieldModel,
				oldOrder: oldOrder
			};

			var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'fieldChangePart', partModel, null, label, data );
		},

		dropNewField: function( e, ui, partModel, partView ) {
			var type = jQuery( ui.draggable ).data( 'id' );
			var fieldModel = this.addField( type, partModel.collection );
			/*
			 * We have a previous part. Add the new field to it.
			 */
			partModel.get( 'formContentData' ).trigger( 'append:field', fieldModel );
		},

		dropStaging: function( e, ui, partModel, partView ) {
			/*
			 * We are dropping a stage
			 */
			
			// Make sure that our staged fields are sorted properly.	
			nfRadio.channel( 'fields' ).request( 'sort:staging' );
			// Grab our staged fields.
			var stagedFields = nfRadio.channel( 'fields' ).request( 'get:staging' );

			_.each( stagedFields.models, function( field, index ) {
				// Add our field.
				var fieldModel = this.addField( field.get( 'slug' ), partModel.collection );
				partModel.get( 'formContentData' ).trigger( 'append:field', fieldModel );
			}, this );

			// Clear our staging
			nfRadio.channel( 'fields' ).request( 'clear:staging' );
		},	

		addField: function( type, collection ) {
			var fieldType = nfRadio.channel( 'fields' ).request( 'get:type', type ); 
			// Add our field
			var fieldModel = nfRadio.channel( 'fields' ).request( 'add', {
				label: fieldType.get( 'nicename' ),
				type: type
			} );

			collection.getFormContentData().trigger( 'remove:field', fieldModel );
			return fieldModel;
		}


	});

	return controller;
} );
/**
 * Handles events for our bottom drawer part title sortable
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define(	'controllers/partSortable',[],	function () {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'mp' ), 'start:partSortable', this.start );
			this.listenTo( nfRadio.channel( 'mp' ), 'stop:partSortable', this.stop );
			this.listenTo( nfRadio.channel( 'mp' ), 'update:partSortable', this.update );
		},

		start: function( e, ui, collection, collectionView ) {
			// If we aren't dragging an item in from types or staging, update our change log.
			if( ! jQuery( ui.item ).hasClass( 'nf-field-type-draggable' ) && ! jQuery( ui.item ).hasClass( 'nf-stage' ) ) { 
				jQuery( ui.item ).css( 'opacity', '0.5' ).show();
				jQuery( ui.helper ).css( 'opacity', '0.75' );
			}
		},

		stop: function( e, ui, collection, collectionView ) {
			// If we aren't dragging an item in from types or staging, update our change log.
			if( ! jQuery( ui.item ).hasClass( 'nf-field-type-draggable' ) && ! jQuery( ui.item ).hasClass( 'nf-stage' ) ) { 
				jQuery( ui.item ).css( 'opacity', '' );
			}
		},

		update: function( e, ui, collection, collectionView ) {
			var partModel = collection.findWhere( { key: jQuery( ui.item ).prop( 'id' ) } );
			/*
			 * Store our current order.
			 */
			var oldOrder = {};
			collection.each( function( partModel, index ) {
				oldOrder[ partModel.get( 'key' ) ] = index;
			} );

			jQuery( ui.item ).css( 'opacity', '' );

			var order = _.without( jQuery( collectionView.el ).sortable( 'toArray' ), '' );
			_.each( order, function( key, index ) {
				collection.findWhere( { key: key } ).set( 'order', index );
			}, this );
			collection.sort();

			/*
			 * Register our part change to the change manager.
			 */
			//Set our 'clean' status to false so that we get a notice to publish changes
			nfRadio.channel( 'app' ).request( 'update:setting', 'clean', false );
			// Update our preview
			nfRadio.channel( 'app' ).request( 'update:db' );

			// Add our field addition to our change log.
			var label = {
				object: 'Part',
				label: partModel.get( 'title' ),
				change: 'Sorted',
				dashicon: 'sort'
			};

			var data = {
				oldOrder: oldOrder,
				collection: collection
			};

			var newChange = nfRadio.channel( 'changes' ).request( 'register:change', 'sortParts', partModel, null, label, data );
		},

	});

	return controller;
} );
/**
 * Respond to undo requests.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/undo',[], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			nfRadio.channel( 'changes' ).reply( 'undo:addPart', this.undoAddPart, this );
			nfRadio.channel( 'changes' ).reply( 'undo:removePart', this.undoRemovePart, this );
			nfRadio.channel( 'changes' ).reply( 'undo:duplicatePart', this.undoDupilcatePart, this );
			nfRadio.channel( 'changes' ).reply( 'undo:fieldChangePart', this.undoFieldChangePart, this );
			nfRadio.channel( 'changes' ).reply( 'undo:sortParts', this.undoSortParts, this );
		},

		undoAddPart: function( change, undoAll ) {
			var partModel = change.get( 'model' );
			var data = change.get( 'data' );
			var partCollection = data.collection;
			partCollection.remove( partModel );

			/*
			 * If we have a fieldModel, then we dragged an existing field to create our part.
			 * Undoing should put that field back where it was.
			 */
			if ( 'undefined' != typeof data.fieldModel ) {
				data.oldPart.get( 'formContentData' ).trigger( 'add:field', data.fieldModel );
			}

			/*
			 * Remove any changes that have this model.
			 */
			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:collection' );
			changeCollection.remove( changeCollection.filter( { model: partModel } ) );
			
			this.maybeRemoveChange( change, undoAll );
		},

		undoFieldChangePart: function( change, undoAll ) {
			var data = change.get( 'data' );
			var oldPart = data.oldPart;
			var fieldModel = data.fieldModel;
			var oldOrder = data.oldOrder;
			var newPart = data.newPart;

			newPart.get( 'formContentData' ).trigger( 'remove:field', fieldModel );
			oldPart.get( 'formContentData' ).trigger( 'add:field', fieldModel );
						
			fieldModel.set( 'order', oldOrder );

			this.maybeRemoveChange( change, undoAll );
		},

		undoRemovePart: function( change, undoAll ) {
			var partModel = change.get( 'model' );
			var data = change.get( 'data' );
			var partCollection = data.collection;
			partCollection.add( partModel );
			
			this.maybeRemoveChange( change, undoAll );
		},

		undoDupilcatePart: function( change, undoAll ) {
			var partModel = change.get( 'model' );
			var data = change.get( 'data' );
			var partCollection = data.collection;
			partCollection.remove( partModel );

			/*
			 * If we have a fieldModel, then we dragged an existing field to create our part.
			 * Undoing should put that field back where it was.
			 */
			if ( 'undefined' != typeof data.fieldModel ) {
				data.oldPart.get( 'formContentData' ).trigger( 'add:field', data.fieldModel );
			}

			/*
			 * Remove any changes that have this model.
			 */
			var changeCollection = nfRadio.channel( 'changes' ).request( 'get:collection' );
			changeCollection.remove( changeCollection.filter( { model: partModel } ) );
			
			this.maybeRemoveChange( change, undoAll );
		},

		undoSortParts: function( change, undoAll ) {
			var collection = change.get( 'data' ).collection;
			var oldOrder = change.get( 'data' ).oldOrder;

			collection.each( function( partModel ) {
				partModel.set( 'order', oldOrder[ partModel.get( 'key' ) ] );
			} );
			collection.sort();

			this.maybeRemoveChange( change, undoAll );
		},

		/**
		 * If our undo action was requested to 'remove' the change from the collection, remove it.
		 * 
		 * @since  3.0
		 * @param  backbone.model 	change 	model of our change
		 * @param  boolean 			remove 	should we remove this item from our change collection
		 * @return void
		 */
		maybeRemoveChange: function( change, undoAll ) {			
			var undoAll = typeof undoAll !== 'undefined' ? undoAll : false;
			if ( ! undoAll ) {
				// Update preview.
				nfRadio.channel( 'app' ).request( 'update:db' );
				var changeCollection = nfRadio.channel( 'changes' ).request( 'get:collection' );
				changeCollection.remove( change );
				if ( 0 == changeCollection.length ) {
					nfRadio.channel( 'app' ).request( 'update:setting', 'clean', true );
					nfRadio.channel( 'app' ).request( 'close:drawer' );
				}
			}
		}
	});

	return controller;
} );
/*
 * Load our builder controllers
 */
define( 
	'controllers/loadControllers',[
		'controllers/data',
		'controllers/clickControls',
		'controllers/gutterDroppables',
		'controllers/partSettings',
		'controllers/partDroppable',
		'controllers/partSortable',
		'controllers/undo'
	], 
	function
	(
		Data,
		ClickControls,
		GutterDroppables,
		PartSettings,
		PartDroppable,
		PartSortable,
		Undo
	)
	{
	var controller = Marionette.Object.extend( {
		initialize: function() {
			new Data();
			new ClickControls();
			new GutterDroppables();
			new PartSettings();
			new PartDroppable();
			new PartSortable();
			new Undo();
		}

	});

	return controller;
} );
/**
 * Top drawer part view
 * 
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'views/drawerItem',[], function() {
	var view = Marionette.ItemView.extend({
		tagName: 'li',
		template: '#nf-tmpl-mp-drawer-item',

		initialize: function( options ) {
			this.collectionView = options.collectionView;
			this.listenTo( this.model, 'change:title', this.updatedTitle );
			this.listenTo( this.model.collection, 'change:part', this.maybeChangeActive );
		},

		updatedTitle: function() {
			this.render();
			this.collectionView.setULWidth( this.collectionView.el );
		},

		maybeChangeActive: function() {
			jQuery( this.el ).removeClass( 'active' );
			if ( this.model == this.model.collection.getElement() ) {
				jQuery( this.el ).addClass( 'active' );
			}
		},

		attributes: function() {
			return {
				id: this.model.get( 'key' )
			}
		},

		onShow: function() {
			var that = this;
			jQuery( this.el ).droppable( {
				activeClass: 'mp-drag-active',
				hoverClass: 'mp-drag-hover',
				accept: '.nf-field-type-draggable, .nf-field-wrap, .nf-stage',
				tolerance: 'pointer',

				over: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'over:part', e, ui, that.model, that );
				},

				out: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'out:part', e, ui, that.model, that );
				},

				drop: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'drop:part', e, ui, that.model, that );
				}
			} );

			this.maybeChangeActive();
		},

		events: {
			'click': 'click',
		},

		click: function( e ) {
			nfRadio.channel( 'mp' ).trigger( 'click:part', e, this.model );
		},

		templateHelpers: function() {
			var that = this;
			return {
				getIndex: function() {
					return that.model.collection.indexOf( that.model ) + 1;
				}
			}
		}
	});

	return view;
} );
/**
 * Drawer collection view.
 * 
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'views/drawerCollection',[ 'views/drawerItem' ], function( DrawerItemView ) {
	var view = Marionette.CollectionView.extend( {
		tagName: 'ul',
		childView: DrawerItemView,
		reorderOnSort: true,
		
		initialize: function( options ) {
			this.drawerLayoutView = options.drawerLayoutView;

			/*
			 * When we resize our window, maybe show/hide pagination.
			 */
			jQuery( window ).on( 'resize', { context: this }, this.resizeEvent );

			/*
			 * If our new part title is off screen in the drawer, scroll to it.
			 */
			this.listenTo( this.collection, 'change:part', this.maybeScroll );
		},

		maybeScroll: function( partCollection ) {
			var li = jQuery( this.el ).children( '#' + partCollection.getElement().get( 'key' ) );
			if ( 0 == jQuery( li ).length ) return false;
			var marginLeft = parseInt( jQuery( li ).css( 'marginLeft' ).replace( 'px', '' ) );
			var viewportWidth = jQuery( this.drawerLayoutView.viewport.el ).width();
			var diff = jQuery( li ).offset().left + jQuery( li ).outerWidth() + marginLeft - viewportWidth;

			jQuery( this.drawerLayoutView.viewport.el ).animate( {
				scrollLeft: '+=' + diff
			}, 100 );
		},

		resizeEvent: function( e ) {
			e.data.context.showHidePagination( e.data.context );
		},

		childViewOptions: function( model, index ){
			var that = this;
			return {
				collectionView: that
			}
		},

		onShow: function() {
			var that = this;
			jQuery( this.el ).sortable( {
				items: 'li:not(.no-sort)',
				helper: 'clone',

				update: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'update:partSortable', e, ui, that.collection, that );
				},

				start: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'start:partSortable', e, ui, that.collection, that );
				},

				stop: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'stop:partSortable', e, ui, that.collection, that );
				}
			} );
		},

		/**
		 * Set our UL width when we attach the html to the dom.
		 *
		 * @since  3.0
		 * @return void
		 */
		onAttach: function() {
			this.setULWidth( this.el );

			/*
			 * When load, hide the pagination arrows if they aren't needed.
			 */
			this.showHidePagination();
		},

		/**
		 * Set the width of our UL based upon the size of its items.
		 * 
		 * @since 3.0
		 * @return void
		 */
		setULWidth: function( el ) {
			if ( 0 == jQuery( el ).find( 'li' ).length ) return;

			var ulWidth = 0;
			jQuery( el ).find( 'li' ).each( function() {
				var marginLeft = parseInt( jQuery( this ).css( 'marginLeft' ).replace( 'px', '' ) );
				ulWidth += ( jQuery( this ).outerWidth() + marginLeft + 2 );
			} );

			jQuery( el ).width( ulWidth );			
		},

		onRemoveChild: function() {
			/* 
			 * Change the size of our collection UL
			 */
			this.setULWidth( this.el );
		},

		onAddChild: function() {
			/* 
			 * Change the size of our collection UL
			 */
			this.setULWidth( this.el );

			this.maybeScroll( this.collection );
		},

		onBeforeAddChild: function( childView ) {
			jQuery( this.el ).css( 'width', '+=100' );
		},

		showHidePagination: function( context, viewportWidth ) {
			context = context || this;

			viewportWidth = viewportWidth || jQuery( context.el ).parent().parent().width() - 120;

			if ( jQuery( context.el ).width() >= viewportWidth ) {
				if ( ! jQuery( context.drawerLayoutView.el ).find( '.nf-mp-drawer-scroll' ).is( ':visible' ) ) {
					jQuery( context.drawerLayoutView.el ).find( '.nf-mp-drawer-scroll' ).show();
				}
			} else {
				if ( jQuery( context.drawerLayoutView.el ).find( '.nf-mp-drawer-scroll' ).is( ':visible' ) ) {
					jQuery( context.drawerLayoutView.el ).find( '.nf-mp-drawer-scroll' ).hide();
					nfRadio.channel( 'app' ).request( 'update:gutters' );
				}
			}
		}
	} );

	return view;
} );
/**
 * Main layout view
 *
 * Regions:
 * mainContent
 * drawer
 * 
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2015 WP Ninjas
 * @since 3.0
 */
define( 'views/drawerLayout',[ 'views/drawerCollection' ], function( DrawerCollectionView ) {
	var view = Marionette.LayoutView.extend({
		tagName: 'div',
		template: '#nf-tmpl-mp-drawer-layout',
		regions: {
			viewport: '#nf-mp-drawer-viewport',
		},

		initialize: function( options ) {
			/*
			 * Make sure that our drawer resizes to match our screen upon resize or drawer open/close.
			 */
			jQuery( window ).on( 'resize', { context: this }, this.resizeWindow );

			this.listenTo( nfRadio.channel( 'drawer' ), 'before:open', this.beforeDrawerOpen );
			this.listenTo( nfRadio.channel( 'drawer' ), 'before:close', this.beforeDrawerClose );
		},

		onBeforeDestroy: function() {
			jQuery( window ).off( 'resize', this.resizeWindow );
		},

		onShow: function() {
			this.viewport.show( new DrawerCollectionView( { collection: this.collection, drawerLayoutView: this } ) );
		},

		/**
		 * When we attach this el to our dom, resize our viewport.
		 * 
		 * @since  3.0
		 * @return void
		 */
		onAttach: function() {
			this.resizeViewport( this.viewport.el );
		},

		/**
		 * Resize our viewport.
		 * 
		 * @since  3.0
		 * @return void
		 */
		resizeViewport: function( viewportEl) {
			/*
			 * If the drawer is closed, our viewport size is based upon the window size.
			 *
			 * If the drawer is opened, our viewport size is based upon the drawer size.
			 */
			var builderEl = nfRadio.channel( 'app' ).request( 'get:builderEl' );
			if ( jQuery( builderEl ).hasClass( 'nf-drawer-opened' ) ) {
				var drawerEl = nfRadio.channel( 'app' ).request( 'get:drawerEl' );
				var targetWidth = targetWidth || jQuery( drawerEl ).outerWidth() - 140;
			} else {
				var targetWidth = targetWidth || jQuery( window ).width() - 140;
			}
			
			jQuery( viewportEl ).width( targetWidth );
		},

		/**
		 * When we resize our browser window, update our viewport size.
		 * 
		 * @since  3.0
		 * @param  {object} e 	event object
		 * @return void
		 */
		resizeWindow: function( e ) {
			e.data.context.resizeViewport( e.data.context.viewport.el );
		},

		beforeDrawerOpen: function() {
			var that = this;
			var drawerEl = nfRadio.channel( 'app' ).request( 'get:drawerEl' );
			var targetWidth = jQuery( drawerEl ).width() - 60;
			
			jQuery( this.viewport.el ).animate( {
				width: targetWidth
			}, 300, function() {
				that.viewport.currentView.showHidePagination( null, targetWidth );
				that.viewport.currentView.maybeScroll( that.collection );
			} );
		},

		beforeDrawerClose: function() {
			var that = this;
			var targetWidth = jQuery( window ).width() - 140;

			jQuery( this.viewport.el ).animate( {
				width: targetWidth
			}, 500, function() {
				that.viewport.currentView.showHidePagination( null, targetWidth );
				that.viewport.currentView.maybeScroll( that.collection );
			} );
		}
	});

	return view;
} );
/**
 * Main layout view
 *
 * Regions:
 * mainContent
 * drawer
 * 
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2015 WP Ninjas
 * @since 3.0
 */
define( 'views/layout',[ 'views/drawerLayout' ], function( DrawerLayoutView ) {
	var view = Marionette.LayoutView.extend({
		tagName: 'div',
		template: '#nf-tmpl-mp-layout',

		regions: {
			mainContent: '#nf-mp-main-content',
			drawer: '#nf-mp-drawer'
		},

		initialize: function() {
			this.listenTo( this.collection, 'change:part', this.changePart );
		},

		onShow: function() {
			this.drawer.show( new DrawerLayoutView( { collection: this.collection } ) );

			/*
			 * Check our fieldContentViewsFilter to see if we have any defined.
			 * If we do, overwrite our default with the view returned from the filter.
			 */
			var formContentViewFilters = nfRadio.channel( 'formContent' ).request( 'get:viewFilters' );
			
			/* 
			* Get our first filter, this will be the one with the highest priority.
			*/
			var sortedArray = _.without( formContentViewFilters, undefined );
			var callback = sortedArray[1];
			this.formContentView = callback();

			this.mainContent.show(  new this.formContentView( { collection: this.collection.getFormContentData() } ) );
		},

		events: {
			'click .nf-mp-drawer-scroll-previous': 'clickPrevious',
			'click .nf-mp-drawer-scroll-next': 'clickNext'
		},

		clickPrevious: function( e ) {
			var that = this;
			var scrollLeft = jQuery( this.drawer.currentView.viewport.el ).scrollLeft();
			var lis = jQuery( this.drawer.currentView.viewport.currentView.el ).find( 'li' );

			jQuery( lis ).each( function( index ) {
				/*
				 * If scrollLeft <= the left of this li, then we know we're at the first visible LI.
				 * Move our scroll to the previous LI and return false.
				 */
				if ( 0 < jQuery( this ).offset().left ) {
					var marginLeft = parseInt( jQuery( this ).css( 'marginLeft' ).replace( 'px', '' ) );
					var scrollLeft = jQuery( jQuery( lis )[ index - 1 ] ).outerWidth() + marginLeft + 5
					jQuery( that.drawer.currentView.viewport.el ).animate( {
						scrollLeft: '-=' + scrollLeft
					}, 300 );
					return false;			
				}
			} );
			

		},

		clickNext: function( e ) {
			var that = this;
			var ULWidth = jQuery( this.drawer.currentView.viewport.currentView.el ).width();
			var viewportWidth = jQuery( this.drawer.currentView.viewport.el ).width();
			var scrollLeft = jQuery( this.drawer.currentView.viewport.el ).scrollLeft();
			var lis = jQuery( this.drawer.currentView.viewport.currentView.el ).find( 'li' );
			var viewportTotal = viewportWidth + scrollLeft;
			var widthCounter = 0;
			var scrollLeft = 0;

			jQuery( lis ).each( function( index ) {
				var marginLeft = parseInt( jQuery( this ).css( 'marginLeft' ).replace( 'px', '' ) );
				widthCounter += ( jQuery( this ).outerWidth() + marginLeft + 5 );
				if ( widthCounter >= viewportTotal ) {
					scrollLeft = jQuery( this ).outerWidth() + marginLeft + 5;
					jQuery( that.drawer.currentView.viewport.el ).animate( {
						scrollLeft: '+=' + scrollLeft
					}, 300 );					
					return false;
				}
			} );
		},

		changePart: function() {
			var currentIndex = this.collection.indexOf( this.collection.getElement() );
			var previousIndex = this.collection.indexOf( this.collection.previousElement );

			if ( currentIndex > previousIndex ) {
				var hideDir = 'left';
				var showDir = 'right';
			} else {
				var hideDir = 'right';
				var showDir = 'left';
			}

			var that = this;
			/*
			 * Start our current part sliding out.
			 */
			jQuery( this.mainContent.el ).hide( 'slide', { direction: hideDir }, 100, function() {
				that.mainContent.empty();
				that.mainContent.show( new that.formContentView( { collection: that.collection.getFormContentData() } ) );
			} );

			jQuery( this.mainContent.el ).show( 'slide', { direction: showDir }, 100 );
			jQuery( this.el ).closest( '.nf-app-main' ).scrollTop( 0 );
		}
	});

	return view;
} );
/**
 * Main content left gutter
 * 
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'views/gutterLeft',[], function() {
	var view = Marionette.ItemView.extend({
		tagName: 'div',
		template: '#nf-tmpl-mp-gutter-left',

		events: {
			'click .fa': 'clickPrevious'
		},

		initialize: function() {
			this.collection = nfRadio.channel( 'mp' ).request( 'get:collection' );
			this.listenTo( this.collection, 'change:part', this.render );
			this.listenTo( this.collection, 'sort', this.render );
			this.listenTo( this.collection, 'remove', this.render );
		},

		onRender: function() {
			var that = this;
			jQuery( this.el ).find( '.fa' ).droppable( {
				// Activate by pointer
				tolerance: 'pointer',
				// Class added when we're dragging over
				hoverClass: 'mp-circle-over',
				activeClass: 'mp-circle-active',
				// Which elements do we want to accept?
				accept: '.nf-field-type-draggable, .nf-field-wrap, .nf-stage',

				/**
				 * When we drag over this droppable, trigger a radio event.
				 * 
				 * @since  3.0
				 * @param  object 	e  event
				 * @param  object 	ui jQuery UI element
				 * @return void
				 */
				over: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'over:gutter', ui, that.collection );
				},

				/**
				 * When we drag out of this droppable, trigger a radio event.
				 * 
				 * @since  3.0
				 * @param  object 	e  event
				 * @param  object 	ui jQuery UI element
				 * @return void
				 */
				out: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'out:gutter', ui, that.collection );
				},

				/**
				 * When we drop on this droppable, trigger a radio event.
				 * 
				 * @since  3.0
				 * @param  object 	e  event
				 * @param  object 	ui jQuery UI element
				 * @return void
				 */
				drop: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'drop:leftGutter', ui, that.collection );
				}
			} );
		},

		clickPrevious: function( e ) {
			nfRadio.channel( 'mp' ).trigger( 'click:previous', e );
		},

		templateHelpers: function() {
			var that = this;
			return {
				hasPrevious: function() {
					return that.collection.hasPrevious();
				}
			}
		},

		changePart: function( context ) {
			context.collection.previous();
		}
	});

	return view;
} );
/**
 * Main content right gutter
 * 
 * @package Ninja Forms builder
 * @subpackage App
 * @copyright (c) 2015 WP Ninjas
 * @since 3.0
 */
define( 'views/gutterRight',[], function() {
	var view = Marionette.ItemView.extend({
		tagName: 'div',
		template: '#nf-tmpl-mp-gutter-right',

		events: {
			'click .next': 'clickNext',
			'click .new': 'clickNew'
		},

		initialize: function() {
			this.collection = nfRadio.channel( 'mp' ).request( 'get:collection' );
			this.listenTo( this.collection, 'change:part', this.render );
			this.listenTo( this.collection, 'sort', this.render );
			this.listenTo( this.collection, 'remove', this.render );
			this.listenTo( this.collection, 'add', this.render );

			this.listenTo( nfRadio.channel( 'fields' ), 'add:field', this.render );
		},

		test: function() {
			console.log( 'test test test' );
		},

		onRender: function() {
			var that = this;
			jQuery( this.el ).find( '.fa' ).droppable( {
				// Activate by pointer
				tolerance: 'pointer',
				// Class added when we're dragging over
				hoverClass: 'mp-circle-over',
				activeClass: 'mp-circle-active',
				// Which elements do we want to accept?
				accept: '.nf-field-type-draggable, .nf-field-wrap, .nf-stage',

				/**
				 * When we drag over this droppable, trigger a radio event.
				 * 
				 * @since  3.0
				 * @param  object 	e  event
				 * @param  object 	ui jQuery UI element
				 * @return void
				 */
				over: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'over:gutter', ui, that.collection );
				},

				/**
				 * When we drag out of this droppable, trigger a radio event.
				 * 
				 * @since  3.0
				 * @param  object 	e  event
				 * @param  object 	ui jQuery UI element
				 * @return void
				 */
				out: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'out:gutter', ui, that.collection );
				},

				/**
				 * When we drop on this droppable, trigger a radio event.
				 * 
				 * @since  3.0
				 * @param  object 	e  event
				 * @param  object 	ui jQuery UI element
				 * @return void
				 */
				drop: function( e, ui ) {
					nfRadio.channel( 'mp' ).trigger( 'drop:rightGutter', ui, that.collection );
				}
			} );
		},

		clickNext: function( e ) {
			nfRadio.channel( 'mp' ).trigger( 'click:next', e );
		},

		clickNew: function( e ) {
			nfRadio.channel( 'mp' ).trigger( 'click:new', e );
		},

		templateHelpers: function() {
			var that = this;
			return {
				hasNext: function() {
					return that.collection.hasNext();
				},

				hasContent: function() {
					return 0 != nfRadio.channel( 'fields' ).request( 'get:collection' ).length;
				}
			}
		},

		changePart: function( context ) {
			context.collection.next();
		}
	});

	return view;
} );
define( 'views/mainContentEmpty',[], function() {
	var view = Marionette.ItemView.extend({
		tagName: 'div',
		template: '#nf-tmpl-mp-main-content-fields-empty',

		onBeforeDestroy: function() {
			jQuery( this.el ).parent().removeClass( 'nf-fields-empty-droppable' ).droppable( 'destroy' );
		},

		onRender: function() {
			this.$el = this.$el.children();
			this.$el.unwrap();
			this.setElement( this.$el );
		},

		onShow: function() {
			if ( jQuery( this.el ).parent().hasClass( 'ui-sortable' ) ) {
				jQuery( this.el ).parent().sortable( 'destroy' );
			}
			jQuery( this.el ).parent().addClass( 'nf-fields-empty-droppable' );
			jQuery( this.el ).parent().droppable( {
				accept: function( draggable ) {
					if ( jQuery( draggable ).hasClass( 'nf-stage' ) || jQuery( draggable ).hasClass( 'nf-field-type-button' ) ) {
						return true;
					}
				},
				activeClass: 'nf-droppable-active',
				hoverClass: 'nf-droppable-hover',
				tolerance: 'pointer',
				over: function( e, ui ) {
					ui.item = ui.draggable;
					nfRadio.channel( 'app' ).request( 'over:fieldsSortable', ui );
				},
				out: function( e, ui ) {
					ui.item = ui.draggable;
					nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );
				},
				drop: function( e, ui ) {
					ui.item = ui.draggable;
					nfRadio.channel( 'app' ).request( 'receive:fieldsSortable', ui );
					var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:collection' );
					fieldCollection.trigger( 'reset', fieldCollection );
				},
			} );
		}
	});

	return view;
} );
/**
 * Add our view and content load filters.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Fields
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define(
	'controllers/filters',[
		'views/layout',
		'views/gutterLeft',
		'views/gutterRight',
		'views/mainContentEmpty',
		'models/partCollection',
	],
	function (
		LayoutView,
		GutterLeftView,
		GutterRightView,
		MainContentEmptyView,
		PartCollection
	)
	{
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'app' ), 'after:loadViews', this.addFilters );
		},

		addFilters: function() {
			nfRadio.channel( 'formContentGutters' ).request( 'add:leftFilter', this.getLeftView, 1, this );
			nfRadio.channel( 'formContentGutters' ).request( 'add:rightFilter', this.getRightView, 1, this );
		
			nfRadio.channel( 'formContent' ).request( 'add:viewFilter', this.getContentView, 1 );
			nfRadio.channel( 'formContent' ).request( 'add:saveFilter', this.formContentSave, 1 );
			
			nfRadio.channel( 'formContent' ).request( 'add:loadFilter', this.formContentLoad, 1 );

			/*
			 * Add a filter so that we can add a "Parts" group to the advanced conditions selects.
			 */
			nfRadio.channel( 'conditions' ).request( 'add:groupFilter', this.conditionsFilter );
			nfRadio.channel( 'conditions-part' ).reply( 'get:triggers', this.conditionTriggers );

			/*
			 * Listen to changes on our "then" statement.
			 */
			// this.listenTo( nfRadio.channel( 'conditions' ), 'change:then', this.maybeAddElse );

			this.emptyView();
		},

		getLeftView: function() {
			return GutterLeftView;
		},

		getRightView: function() {
			return GutterRightView;
		},

		formContentLoad: function( formContentData ) {
			/*
			 * If the data has already been converted, just return it.
			 */
			if ( true === formContentData instanceof PartCollection ) return formContentData;

			/*
			 * If the data isn't converted, but is an array, let's make sure it's part data.
			 */
			if ( _.isArray( formContentData ) && ! _.isEmpty( formContentData )  && 'undefined' != typeof _.first( formContentData ) && 'part' == _.first( formContentData ).type ) {
				/*
				 * We have multi-part data. Let's convert it to a collection.
				 */
				var partCollection = new PartCollection( formContentData );
			} else {
				formContentData = ( 'undefined' == typeof formContentData ) ? nfRadio.channel( 'fields' ).request( 'get:collection' ).pluck( 'key' ) : formContentData; 

				/*
				 * We have unknown data. Create a new part collection and use the data as the content.
				 */
				var partCollection = new PartCollection( { formContentData: formContentData } );
			}
			nfRadio.channel( 'mp' ).request( 'init:partCollection', partCollection );
			return partCollection;
		},

		getContentView: function() {
			return LayoutView;
		},

		formContentSave: function( partCollection ) {
			/*
			 * For each of our part models, call the next save filter for its formContentData
			 */
			var newCollection = new Backbone.Collection();
			/*
			 * If we don't have a filter for our formContentData, default to fieldCollection.
			 */
			var formContentSaveFilters = nfRadio.channel( 'formContent' ).request( 'get:saveFilters' );
			
			partCollection.each( function( partModel ) {
				var attributes = _.clone( partModel.attributes );

				/* 
				 * Get our first filter, this will be the one with the highest priority.
				 */
				var sortedArray = _.without( formContentSaveFilters, undefined );
				var callback = sortedArray[1];
				var formContentData = callback( attributes.formContentData );
				attributes.formContentData = formContentData;

				newCollection.add( attributes );
			} );

			return newCollection.toJSON();
		},

		emptyView: function() {
			this.defaultMainContentEmptyView = nfRadio.channel( 'views' ).request( 'get:mainContentEmpty' );
			nfRadio.channel( 'views' ).reply( 'get:mainContentEmpty', this.getMainContentEmpty, this );
		},

		getMainContentEmpty: function() {
			if ( 1 == nfRadio.channel( 'mp' ).request( 'get:collection' ).length ) {
				return this.defaultMainContentEmptyView;
			} else {
				return MainContentEmptyView;
			}
		},

		conditionsFilter: function( groups, modelType ) {
			var partCollection = nfRadio.channel( 'mp' ).request( 'get:collection' );
			if ( 0 == partCollection.length || 'when' == modelType ) return groups;

			var partOptions = partCollection.map( function( part ) {
				return { key: part.get( 'key' ), label: part.get( 'title' ) };
			} );

			groups.unshift( { label: 'Parts', type: 'part', options: partOptions } );
			return groups;
		},

		conditionTriggers: function( defaultTriggers ) {
			return {
				show_field: {
					label: 'Show Part',
					value: 'show_part'
				},

				hide_field: {
					label: 'Hide Part',
					value: 'hide_part'
				}
			};
		},

		/**
		 * When we change our then condition, if we are show/hiding a part add the opposite.
		 * 
		 * @since  3.0
		 * @param  {[type]} e         [description]
		 * @param  {[type]} thenModel [description]
		 * @return {[type]}           [description]
		 */
		maybeAddElse: function( e, thenModel ) {
			var opposite = false;
			/*
			 * TODO: Make this more dynamic.
			 * Currently, show, hide, show option, and hide option are hard-coded here.
			 */
			var trigger = jQuery( e.target ).val();
			switch( trigger ) {
				case 'show_part':
					opposite = 'hide_part';
					break;
				case 'hide_part':
					opposite = 'show_part';
					break;
			}

			if ( opposite ) {
				var conditionModel = thenModel.collection.options.conditionModel;
				if( 'undefined' == typeof conditionModel.get( 'else' ).findWhere( { 'key': thenModel.get( 'key' ), 'trigger': opposite } ) ) {
					conditionModel.get( 'else' ).add( { type: thenModel.get( 'type' ), key: thenModel.get( 'key' ), trigger: opposite } );
				}
			}
		}
	});

	return controller;
} );
var nfRadio = Backbone.Radio;

require( [ 'controllers/loadControllers', 'controllers/filters' ], function( LoadControllers, LoadFilters ) {

	var NFMultiPart = Marionette.Application.extend( {

		initialize: function( options ) {
			this.listenTo( nfRadio.channel( 'app' ), 'after:loadControllers', this.loadControllers );
		},

		loadControllers: function() {
			new LoadControllers();
		},

		onStart: function() {
			new LoadFilters();
		}
	} );

	var nfMultiPart = new NFMultiPart();
	nfMultiPart.start();
} );
define("main", function(){});

}());
//# sourceMappingURL=builder.js.map
