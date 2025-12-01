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

define( 'views/header',[], function() {
	var view = Marionette.ItemView.extend( {
		template: "#tmpl-nf-mp-header",
 		fullProgressBar: false,

 		initialize: function( options ) {
			this.listenTo( this.collection, 'change:part', this.reRender );
			this.listenTo( this.collection, 'change:errors', this.reRender );

			this.listenTo( nfRadio.channel( 'forms' ), 'before:submit', this.fillProgressBar );
		},

		reRender: function() {
			this.model = this.collection.getElement();
			this.render();
		},

		templateHelpers: function() {
			var that = this;
			return {
				renderPartTitle: function() {
					if ( 'undefined' == typeof that.collection.formModel.get( 'settings' ).mp_display_titles || 0 == that.collection.formModel.get( 'settings' ).mp_display_titles ) return '';
					
					var template = Backbone.Radio.channel( 'app' ).request( 'get:template', '#tmpl-nf-mp-part-title' );
					var parts = _.invoke( that.collection.getVisibleParts(), 'pick', [ 'title', 'errors', 'visible' ] )
					if ( 1 < parts.length ) {
						return template( { title: this.title } );
					} else {
						return ''; 
					}
				},

				renderBreadcrumbs: function() {
					if ( 'undefined' == typeof that.collection.formModel.get( 'settings' ).mp_breadcrumb || 0 == that.collection.formModel.get( 'settings' ).mp_breadcrumb ) return '';

					var template = Backbone.Radio.channel( 'app' ).request( 'get:template', '#tmpl-nf-mp-breadcrumbs' );
					var parts = _.invoke( that.collection.getVisibleParts(), 'pick', [ 'title', 'errors', 'visible' ] )
					if ( 1 < parts.length ) {
						return template( { parts: parts, currentIndex: that.collection.getVisibleParts().indexOf( that.model ) } );
					} else {
						return ''; 
					}
				},

				renderProgressBar: function() {
					if ( 'undefined' == typeof that.collection.formModel.get( 'settings' ).mp_progress_bar || 0 == that.collection.formModel.get( 'settings' ).mp_progress_bar ) return '';
					
					var template = Backbone.Radio.channel( 'app' ).request( 'get:template', '#tmpl-nf-mp-progress-bar' );
					var currentIndex = that.collection.getVisibleParts().indexOf( that.model );
					var percent = ( that.fullProgressBar ) ? 100 : currentIndex / that.collection.getVisibleParts().length * 100;
					if ( 1 < that.collection.getVisibleParts().length ) {
						return template( { percent: percent } );
					} else {
						return '';
					}
				}
			}
		},

		events: {
			'click .nf-breadcrumb': 'clickBreadcrumb'
		},

		clickBreadcrumb: function( e ) {
			e.preventDefault();
			this.collection.setElement( this.collection.getVisibleParts()[ jQuery( e.target ).data( 'index' ) ] );
		},

		fillProgressBar: function( formModel ) {
			this.fullProgressBar = true;
			this.render();
			this.fullProgressBar = false;
		}
	} );

	return view;
} );
define( 'views/footer',[], function() {
	var view = Marionette.ItemView.extend( {
		template: "#tmpl-nf-mp-footer",

		initialize: function( options ) {
			this.listenTo( this.collection, 'change:part', this.reRender );
		},

		reRender: function() {
			this.model = this.collection.getElement();
			this.render();
		},

		templateHelpers: function() {
			var that = this;
			return {
				renderNextPrevious: function() {
					var template = Backbone.Radio.channel( 'app' ).request( 'get:template', '#tmpl-nf-mp-next-previous' );
					var showNext = false;
					var showPrevious = false;
					var visibleParts = that.collection.where( { visible: true } );
					
					/*
					 * If our collection pointer isn't on the last visible part, show Next navigation.
					 */
					if ( visibleParts.indexOf( that.model ) != visibleParts.length - 1 ) {
						showNext = true;
					}

					/*
					 * If our collection pointer isn't on the first visible part, show Previous navigation.
					 */
					if ( visibleParts.indexOf( that.model ) != 0 ) {
						showPrevious = true;
					}

					if ( ! showNext && ! showPrevious ) return '';

					var prevLabel = that.collection.formModel.get( 'mp_prev_label' ) || nfMPSettings.prevLabel;
					var nextLabel = that.collection.formModel.get( 'mp_next_label' ) || nfMPSettings.nextLabel;

					return template( { showNext: showNext, showPrevious: showPrevious, prevLabel: prevLabel, nextLabel: nextLabel } );
				},
			}
		}

	} );

	return view;
} );

define( 'views/formContent',[ 'views/header', 'views/footer' ], function( HeaderView, FooterView ) {
	var view = Marionette.LayoutView.extend( {
		template: "#tmpl-nf-mp-form-content",

		regions: {
			header: '.nf-mp-header',
			body: '.nf-mp-body',
			footer: '.nf-mp-footer'
		},

		initialize: function( options ) {
			this.formModel = options.formModel;
			this.collection = options.data;
			this.listenTo( this.collection, 'change:part', this.changePart );
			this.listenTo( this.collection, 'change:visible', this.renderHeaderFooter );
		},

		onRender: function() {
			this.header.show( new HeaderView( { collection: this.collection, model: this.collection.getElement() } ) );

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
			
			this.body.show(  new this.formContentView( { collection: this.collection.getElement().get( 'formContentData' ) } ) );
			this.footer.show( new FooterView( { collection: this.collection, model: this.collection.getElement() } ) );
		},

		renderHeaderFooter: function() {
			this.header.show( new HeaderView( { collection: this.collection, model: this.collection.getElement() } ) );
			this.footer.show( new FooterView( { collection: this.collection, model: this.collection.getElement() } ) );
		},

		changePart: function() {
			this.body.show(  new this.formContentView( { collection: this.collection.getElement().get( 'formContentData' ) } ) );
			/*
			 * Scroll the page to the top of the form.
			 */
			var formTop = jQuery( this.body.el ).closest( '.nf-form-cont' ).offset().top;
			if ( jQuery( window ).scrollTop() > formTop - 50 ) {
				jQuery( window ).scrollTop( formTop - 50 );
			}
		},

		events: {
			'click .nf-next': 'clickNext',
			'click .nf-previous': 'clickPrevious'
		},

		clickNext: function( e ) {
			e.preventDefault();
			this.collection.next();
		},

		clickPrevious: function( e ) {
			e.preventDefault();
			this.collection.previous();
		}

	} );

	return view;
} );
define( 'models/partModel',[], function() {
	var model = Backbone.Model.extend( {
		fieldErrors: {},

		defaults: {
			errors: false,
			visible: true,
			title: ''
		},

		initialize: function() {
			this.filterFormContentData();
			this.listenTo( this.get( 'formContentData' ), 'change:errors', this.maybeChangeActivePart );
			this.fieldErrors[ this.cid ] = [];
			this.on( 'change:visible', this.changeVisible, this );
            // Cast order as a number to avoid string comparison.
            this.set( 'order', Number( this.get( 'order' ) ) );
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

			this.set( 'formContentData', callback( formContentData, this.collection.formModel, empty, formContentData ) );
		},

		maybeChangeActivePart: function( fieldModel ) {
			/*
			 * If we have an error on this part, add an error to our part model.
			 *
			 * If we are on a part that has a higher index than the current part, set this as current.
			 */
			if ( 0 < fieldModel.get( 'errors' ).length ) {
				this.set( 'errors', true );
				this.fieldErrors[ this.cid ].push( fieldModel.get( 'key' ) );
				// this.set( 'fieldErrors', fieldModel.get( 'key' ) );
				if (
					this.collection.getElement() != this &&
					this.collection.indexOf( this.collection.getElement() ) > this.collection.indexOf( this )

				) {
					this.collection.setElement( this );
				}
			} else {
				this.fieldErrors[ this.cid ] = _.without( this.fieldErrors[ this.cid ], fieldModel.get( 'key' ) );
				if ( 0 == this.fieldErrors[ this.cid ].length ) {
					this.set( 'errors', false );
				}
			}
		},

		validateFields: function() {
			this.get( 'formContentData' ).validateFields();
		},

		changeVisible: function() {
			if ( this.get( 'visible' ) ) {
				this.get( 'formContentData' ).showFields();
			} else {
				this.get( 'formContentData' ).hideFields();
			}
		}
	} );

	return model;
} );

define( 'models/partCollection',[ 'models/partModel' ], function( PartModel ) {
	var collection = Backbone.Collection.extend( {
		model: PartModel,
		currentElement: false,

		initialize: function( models, options ){
			this.formModel = options.formModel;
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
			silent = silent || false;

			/*
			 * If we have part errors and aren't updating silently, check for part errors.
			 */
			if ( ! silent ) {
				if ( this.partErrors() ) return;
			}
			
			this.currentElement = model;
			if ( ! silent ) {
				this.trigger( 'change:part', this );
				nfRadio.channel( 'nfMP' ).trigger( 'change:part', this );
			} 
		},
		  
		// check for errors on Next click if we have validate parts on
		setNextElement: function( model, silent ) {
			silent = silent || false;

			/*
			 * If we have part errors and aren't updating silently, check for part errors.
			 */
			if ( ! silent ) {
				if ( this.partErrors() ) return;
			}
			
			this.currentElement = model;
			if ( ! silent ) {
				this.trigger( 'change:part', this );
				nfRadio.channel( 'nfMP' ).trigger( 'change:part', this );
			} 
		},

		// We don't want to stop user from moving back in the form if there are errors
		setPreviousElement: function( model, silent ) {
			silent = silent || false;
			
			this.currentElement = model;
			if ( ! silent ) {
				this.trigger( 'change:part', this );
				nfRadio.channel( 'nfMP' ).trigger( 'change:part', this );
			}
		},
		
		next: function (){
			/*
			 * If this isn't the last visible part, move forward.
			 */
			if ( this.getVisibleParts().length - 1 != this.getVisibleParts().indexOf( this.getElement() ) ) {
				this.setNextElement( this.getVisibleParts()[ this.getVisibleParts().indexOf( this.getElement() ) + 1 ] );
			}
			
			return this;
		},

		previous: function() {
			/*
			 * If this isn't the first visible part, move backward.
			 */
			if ( 0 != this.getVisibleParts().indexOf( this.getElement() ) ) {
				this.setPreviousElement( this.getVisibleParts()[ this.getVisibleParts().indexOf( this.getElement() ) - 1 ] );	
			}
			
			return this;
		},

		partErrors: function() {
			if ( 'undefined' == typeof this.formModel.get( 'settings' ).mp_validate || 0 == this.formModel.get( 'settings' ).mp_validate ) return false;
			/*
			 * Check to see if our parts have any errors.
			 */
			this.currentElement.validateFields();
			return this.currentElement.get( 'errors' );
		},

		validateFields: function() {
			/*
			 * call validateFields on each visible part
			 */
			_.each( this.getVisibleParts(), function( partModel ) { partModel.validateFields(); } );
		},

		getVisibleParts: function() {
			return this.where( { visible: true } );
		}
	} );

	return collection;
} );
/**
 * Interprets formContent data when a form is loaded.
 * Also returns our MP Layout View to use in place of the standard form layout view.
 * 
 * @package Ninja Forms Front-End
 * @subpackage Main App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/loadFilters',[ 'views/formContent', 'models/partCollection' ], function( FormContentView, PartCollection ) {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			nfRadio.channel( 'formContent' ).request( 'add:viewFilter', this.getformContentView, 1 );
			nfRadio.channel( 'formContent' ).request( 'add:loadFilter', this.formContentLoad, 1 );
		},

		/**
		 * Return the MP Content View
		 * 
		 * @since  3.0
		 * @param  {Backbon.Collection} collection formContentData
		 * @return {Backbone.View}            Our MP Content View
		 */
		getformContentView: function( formContentData ) {
			return FormContentView;
		},

		/**
		 * When we load our front-end view, we filter the formContentData.
		 * This turns the saved object into a Backbone Collection.
		 * 
		 * @since  3.0
		 * @param  array formContentData current value of our formContentData.
		 * @return Backbone.Collection
		 */
		formContentLoad: function( formContentData, formModel ) {
			/*
			 * If the data has already been converted, just return it.
			 */
			if ( true === formContentData instanceof PartCollection ) return formContentData;
			/*
			 * If the data isn't converted, but is an array, let's make sure it's part data.
			 */
			if ( _.isArray( formContentData ) && 0 != _.isArray( formContentData ).length  && 'undefined' != typeof _.first( formContentData ) && 'part' == _.first( formContentData ).type ) {
				/*
				 * We have multi-part data. Let's convert it to a collection.
				 */
				var partCollection = new PartCollection( formContentData, { formModel: formModel } );
			} else {
				/*
				 * We have unknown data. Create a new part collection and use the data as the content.
				 */
				var partCollection = new PartCollection( { formContentData: formContentData }, { formModel: formModel } );
			}

			return partCollection;
		}
	});

	return controller;
} );
/**
 * Handles showing and hiding parts in response to Conditional Logic triggers.
 * 
 * @package Ninja Forms Front-End
 * @subpackage Main App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/conditionalLogic',[], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			nfRadio.channel( 'condition:trigger' ).reply( 'show_part', this.showPart, this );
			nfRadio.channel( 'condition:trigger' ).reply( 'hide_part', this.hidePart, this );
		},

		showPart: function( conditionModel, then ) {
			conditionModel.set( 'alreadyTriggered', true );
			this.changePartVisibility( conditionModel, then, true );
			conditionModel.set( 'alreadyTriggered', false );
		},

		hidePart: function( conditionModel, then ) {
			conditionModel.set( 'alreadyTriggered', true );
			this.changePartVisibility( conditionModel, then, false );
			conditionModel.set( 'alreadyTriggered', false );
		},

		changePartVisibility: function( conditionModel, then, visible ) {


            /**
			 * Multi-Part Reset Flag
			 *   Identifies the initial request of a nested loop of conditions
			 *   so that the alreadyTriggered flags can be cleared.
             */
			var mpResetFlag = Date.now();
			if( ! conditionModel.collection.mpResetFlag ){
                conditionModel.collection.mpResetFlag = mpResetFlag;
			}

			var partCollection = conditionModel.collection.formModel.get( 'formContentData' );
			partCollection.findWhere( { key: then.key } ).set( 'visible', visible );

			// Check our conditions again because we have just shown/hidden a part that could have conditions on it.
			conditionModel.collection.each( function( model ) {
				// Avoid triggering a model's own conditions.
				if( model == conditionModel ) return;
				// Avoid re-triggering conditions, which may cause an infinite loop.
				if( model.get( 'alreadyTriggered' ) ) return;
				// Trigger conditions.
                model.checkWhen();
                // Set a flag to avoid re-triggering these conditions.
                model.set( 'alreadyTriggered', true );
			} );

            /**
			 * Reset Flag Check
			 *   If is the initial request of the nested loop of conditions
			 *   then clear the previously set alreadyTriggered flags.
             */
			if( mpResetFlag == conditionModel.collection.mpResetFlag ){
				// Clear all alreadyTriggered flags.
                conditionModel.collection.invoke( 'set', { 'alreadyTriggered': false } );
                // Clear the Reset Flag.
                conditionModel.collection.mpResetFlag = false;
			}
		}
	});

	return controller;
} );

/**
 * Handles making sure that any google recaptcha fields render if they are on this part.
 * 
 * @package Ninja Forms Front-End
 * @subpackage Main App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/renderRecaptcha',[], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'nfMP' ), 'change:part', this.changePart, this );
		},

		changePart: function( conditionModel, then ) {
			jQuery( '.g-recaptcha' ).each( function() {
                var callback = jQuery( this ).data( 'callback' );
                var fieldID = jQuery( this ).data( 'fieldid' );
                if ( typeof window[ callback ] !== 'function' ){
                    window[ callback ] = function( response ) {
                        nfRadio.channel( 'recaptcha' ).request( 'update:response', response, fieldID );
                    };
                }
				var opts = {
					theme: jQuery( this ).data( 'theme' ),
					sitekey: jQuery( this ).data( 'sitekey' ),
					callback: callback
				};
				
				grecaptcha.render( jQuery( this )[0], opts );
			} );
		},

	});

	return controller;
} );
/**
 * Handles making sure that any Cloudflare Turnstile fields render if they are on this part.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Front-End Controllers
 * @copyright (c) 2024 WP Ninjas
 * @since 3.0
 */
define( 'controllers/renderTurnstile',[], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'nfMP' ), 'change:part', this.changePart, this );
		},

		changePart: function( conditionModel, then ) {
			// Wait for part transition to complete before re-rendering
			setTimeout( function() {
				jQuery( '.cf-turnstile, .nf-cf-turnstile' ).each( function() {
					var element = this;
					var sitekey = jQuery( element ).data( 'sitekey' );
					var fieldID = jQuery( element ).data( 'fieldid' );
					
					// Skip if no sitekey or already rendered
					if ( ! sitekey || jQuery( element ).children().length > 0 ) {
						return;
					}

					// Ensure turnstile API is loaded
					if ( typeof turnstile === 'undefined' ) {
						return;
					}

					try {
						// Create callback function for this specific field
						var callbackName = 'nfTurnstileCallback_' + fieldID;
						
						// Define the callback function
						window[ callbackName ] = function( token ) {
							// Update the hidden input field
							var input = document.getElementById( 'nf-field-' + fieldID );
							if ( input ) {
								input.value = token;
								jQuery( input ).trigger( 'change' );
								
								// Remove error states
								jQuery( input ).closest( '.field-wrap' ).removeClass( 'nf-error' );
								jQuery( input ).closest( '.field-wrap' ).find( '.nf-error-msg' ).remove();
							}

							// Update via radio channel if available
							if ( typeof nfRadio !== 'undefined' && nfRadio.channel ) {
								try {
									nfRadio.channel( 'turnstile' ).request( 'update:response', token, fieldID );
								} catch( e ) {
									// Silent fail
								}
							}
						};

						// Render the turnstile widget
						turnstile.render( element, {
							sitekey: sitekey,
							theme: jQuery( element ).data( 'theme' ) || 'auto',
							size: jQuery( element ).data( 'size' ) || 'normal',
							callback: callbackName
						} );
						
					} catch( e ) {
						// Silent fail for turnstile render errors
						console.warn( 'Turnstile render error:', e );
					}
				} );
			}, 100 ); // Small delay to ensure DOM is ready
		}
	});

	return controller;
} );
/**
 * Handles making sure that any help text render if they are on this part.
 *
 * @package Ninja Forms Front-End
 * @subpackage Main App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( 'controllers/renderHelpText',[], function() {
    var controller = Marionette.Object.extend( {
        initialize: function() {
            this.listenTo( nfRadio.channel( 'nfMP' ), 'change:part', this.changePart, this );
        },

        changePart: function( conditionModel, then ) {
            jQuery( '.nf-help' ).each( function() {
                var jBox = jQuery( this ).jBox( 'Tooltip', {
                    theme: 'TooltipBorder',
                    content: jQuery( this ).data( 'text' )
                });
            } );
        },

    });

    return controller;
} );
define( 
	'controllers/loadControllers',[
		'controllers/conditionalLogic',
		'controllers/renderRecaptcha',
		'controllers/renderTurnstile',
		'controllers/renderHelpText'
	],
	function
	(
		ConditionalLogic,
		RenderRecaptcha,
		RenderTurnstile,
		RenderHelpText
	)
	{
	var controller = Marionette.Object.extend( {
		initialize: function() {
			new ConditionalLogic();
			new RenderRecaptcha();
			new RenderTurnstile();
			new RenderHelpText();
		}

	});

	return controller;
} );
var nfRadio = Backbone.Radio;

require( [ 'controllers/loadFilters', 'controllers/loadControllers' ], function( LoadFilters, LoadControllers ) {

	var NFMultiPart = Marionette.Application.extend( {

		initialize: function( options ) {
			// console.log( 'init mp' );
			this.listenTo( nfRadio.channel( 'form' ), 'before:filterData', this.loadFilters );
			this.listenTo( nfRadio.channel( 'form' ), 'loaded', this.loadControllers );
		},

		loadFilters: function( formModel ) {
			new LoadFilters();
		},

		loadControllers: function( formModel ) {
			new LoadControllers();
		},

		onStart: function() {
		}
	} );

	var nfMultiPart = new NFMultiPart();
	nfMultiPart.start();
} );
define("main", function(){});

}());
//# sourceMappingURL=front-end.js.map
