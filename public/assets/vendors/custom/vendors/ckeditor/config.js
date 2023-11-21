/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

 CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'en';
	// config.uiColor = '#AADC6E';

   config.toolbar_Full =
   [
   { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
   { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
   { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
   { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
   'HiddenField' ] },
   '/',
   { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
   { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
   '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
   { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
   { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
   '/',
   { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
   { name: 'colors', items : [ 'TextColor','BGColor' ] },
   { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
   ];

   config.toolbar_Basic =
   [
   ['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'FontSize', 'Font', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-', 'Table']
   ];

   config.toolbar_Custom =
   [
   { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
   { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
   { name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar','Iframe' ] },
   { name: 'document', items : [ 'Source' ] },
   '/',
   { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
   { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','-','Blockquote',
   '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-' ] },
   '/',
   { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
   { name: 'colors', items : [ 'TextColor','BGColor' ] },
   { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
   ];
};

CKEDITOR.on( 'dialogDefinition', function( ev )
   {
      var dialogName = ev.data.name;
      var dialogDefinition = ev.data.definition;

      if (dialogName == 'image' || dialogName == 'flash')
      {
         // remove Upload tab
         dialogDefinition.removeContents( 'Upload' );
      }
   });
