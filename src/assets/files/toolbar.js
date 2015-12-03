$(function() {

	var ogTemplate;

	var yiiSeoTool = {
		container: '.toolbar-inner',
		toggler: '.yii-seo-toolbar-logo',
		ogList: '.yii-seo-og-list',
		ogGroup: 'div.form-group',
		setVisible: function() {
			var visible = localStorage.getItem('yiiSeoTool');

			if (visible == 0) {
				$(yiiSeoTool.container).addClass('collapsed');
			} else {
				$(yiiSeoTool.container).removeClass('collapsed');
			}
		},
		addOgTag: function () {
			var item = $('<div class="form-group" />').append(ogTemplate.html().replace(/\[ogTags\]\[[0-9]+\]/g, function() {
				return "[ogTags][" + $(yiiSeoTool.ogList + ' ' + yiiSeoTool.ogGroup).length + "]";
			}));
			$(yiiSeoTool.ogList).append(item);
			item.find('input').val('');
		}
	};

	ogTemplate = $(yiiSeoTool.ogList + ' ' + yiiSeoTool.ogGroup).first().clone();

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