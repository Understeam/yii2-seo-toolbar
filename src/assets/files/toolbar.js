$(function() {
	var yiiSeoTool = {
		container: '.toolbar-inner',
		toggler: '.yii-seo-toolbar-logo',
		ogList: '.yii-seo-og-list',
		setVisible: function() {
			var visible = localStorage.getItem('yiiSeoTool');

			if (visible == 0) {
				$(yiiSeoTool.container).addClass('collapsed');
			} else {
				$(yiiSeoTool.container).removeClass('collapsed');
			}
		},
		addOgTag: function () {
			var ogTemplate = $(yiiSeoTool.ogList).first().html();
			$(yiiSeoTool.ogList).append(ogTemplate);
		}
	};

	localStorage.getItem('yiiSeoTool') ? yiiSeoTool.setVisible() : localStorage.setItem('yiiSeoTool', 0);

	$(document).on('click', yiiSeoTool.toggler, function(e) {
		e.preventDefault();
		$(yiiSeoTool.container).toggleClass('collapsed');

		if ($(yiiSeoTool.container).hasClass('collapsed')) {
			localStorage.setItem('yiiSeoTool', 0);
		} else {
			localStorage.setItem('yiiSeoTool', 1);
		}
	});

	$(document).on('click', '.addTag', function(e) {
		e.preventDefault();
		yiiSeoTool.addOgTag();
	});
});