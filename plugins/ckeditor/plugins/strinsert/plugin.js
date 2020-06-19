/**
 * @license Copyright © 2013 Stuart Sillitoe <stuart@vericode.co.uk>
 * This work is mine, and yours. You can modify it as you wish.
 *
 * Stuart Sillitoe
 * stuartsillitoe.co.uk
 *
 */

CKEDITOR.plugins.add('strinsert',
{
	requires : ['richcombo'],
	init : function( editor )
	{
		//  array of strings to choose from that'll be inserted into the editor
		var strings = [];
		strings.push(['#FIRST_NAME#', 'First Name', 'First Name']);
		strings.push(['#LAST_NAME#', 'Last Name', 'Last Name']);
		strings.push(['#MOBILE#', 'Mobile', 'Mobile']);
		strings.push(['#EMAIL_ADDRESS#', 'Email', 'Email']);
		strings.push(['#UNSUBSCRIBE_LINK#', 'Unsubscribe Button', 'Unsubscribe Button']);

		// add the menu to the editor
		editor.ui.addRichCombo('strinsert',
		{
			label: 		'Variable',
			title: 		'Variable',
			voiceLabel: 'Variable',
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				this.startGroup( "Variable" );
				for (var i in strings)
				{
					this.add(strings[i][0], strings[i][1], strings[i][2]);
				}
			},

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});
	}
});