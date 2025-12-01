define( 
	[
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