<?php

add_filter('acf/load_field/name=datenow', function ($field) {
    $field['default_value'] = date('Y-m-d H:i:s', strtotime('now'));
    return $field;
});


// =================================================
//	:: ACF WYSIWYG with 'bold' only toolbar::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'bold_only'  );
function bold_only( $toolbars )
{
	$toolbars['Bold Only' ] = array();
	$toolbars['Bold Only' ][1] = array('bold');

	return $toolbars;
}	


// =================================================
//	:: ACF WYSIWYG with 'bold' and 'link' only toolbar::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'bold_link'  );
function bold_link( $toolbars )
{
	$toolbars['Bold and Link' ] = array();
	$toolbars['Bold and Link' ][1] = array('bold','link');

	return $toolbars;
}	



// =================================================
//  :: ACF WYSIWYG with 'bold', 'link' and 'alignment' only toolbar::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'bold_link_alignment'  );
function bold_link_alignment( $toolbars )
{
    $toolbars['Bold, Link and Alignment' ] = array();
    $toolbars['Bold, Link and Alignment' ][1] = array('bold','link','alignleft','aligncenter','alignright');

    return $toolbars;
}   



// =================================================
//  :: ACF WYSIWYG Visual for Title::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'formats_bold_alignment'  );
function formats_bold_alignment( $toolbars )
{   //error_log(print_r($toolbars));
    $toolbars['Formats, Bold and Alignment' ] = array();
    $toolbars['Formats, Bold and Alignment' ][1] = array('formatselect','bold','alignleft','aligncenter','alignright');

    return $toolbars;
}


// =================================================
//  :: ACF WYSIWYG with 'bold', 'italic' and 'link' only toolbar::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'bold_italic_alignment_link'  );
function bold_italic_alignment_link( $toolbars )
{
    $toolbars['Bold, Italic, Alignment and Link' ] = array();
    $toolbars['Bold, Italic Alignment and Link' ][1] = array('bold','italic','link','alignleft','aligncenter','alignright');

    return $toolbars;
}  



// =================================================
//  :: ACF WYSIWYG with 'format', bold', 'italic' and 'link' only toolbar::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'format_bold_italic_alignment_link'  );
function format_bold_italic_alignment_link( $toolbars )
{
    $toolbars['Formats, Bold, Italic, Alignment and Link' ] = array();
    $toolbars['Formats, Bold, Italic Alignment and Link' ][1] = array('formatselect','styleselect','bold','italic','link','alignleft','aligncenter','alignright');

    return $toolbars;
} 

// =================================================
//  :: ACF WYSIWYG Visual for Title::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'bold_alignment'  );
function bold_alignment( $toolbars )
{   //error_log(print_r($toolbars));
    $toolbars['Bold and Alignment' ] = array();
    $toolbars['Bold and Alignment' ][1] = array('bold','alignleft','aligncenter','alignright');

    return $toolbars;
}


// =================================================
//  :: ACF WYSIWYG with 'bold' and 'inline style' only toolbar::
// -------------------------------------------------
add_filter( 'acf/fields/wysiwyg/toolbars' , 'bold_formats' );
function bold_formats( $toolbars )
{
    $toolbars['Bold and Formats' ] = array();
    $toolbars['Bold and Formats' ][1] = array('bold','formats');

    return $toolbars;
}


add_action('acf/input/admin_head', 'bold_only_editor_height'); 
function bold_only_editor_height() {
        ?>
        <style type="text/css">

        .acf-field .acf-editor-wrap[data-toolbar="bold_only"] iframe {
            height: 140px !important;
            min-height: 140px;
        }

        .acf-field .acf-editor-wrap[data-toolbar="bold_only"] div.mce-statusbar{
        	display: none;
        }

        </style>
        <?php
}

//add_action('acf/input/admin_head', 'bold_link_only_editor_height'); 
function bold_link_only_editor_height() {
        ?>
        <style type="text/css">

        .acf-field .acf-editor-wrap[data-toolbar="bold_and_link"] iframe {
            height: 140px !important;
            min-height: 140px;
        }

        .acf-field .acf-editor-wrap[data-toolbar="bold_and_link"] div.mce-statusbar{
            display: none;
        }



        </style>
        <?php
}




add_action('acf/input/admin_head', 'formats_bold_alignment_height'); 
function formats_bold_alignment_height() {
        ?>
        <style type="text/css">

        .acf-field .acf-editor-wrap[data-toolbar="formats_bold_alignment"] iframe {
            height: 200px !important;
            min-height: 200px;
        }

        .acf-field .acf-editor-wrap[data-toolbar="formats_bold_alignment"] div.mce-statusbar{
            display: none;
        }



        </style>
        <?php
}
add_action('acf/input/admin_head', 'formats_bold_italic_alignment_link_height'); 
function formats_bold_italic_alignment_link_height() {
        ?>
        <style type="text/css">

        .acf-field .acf-editor-wrap[data-toolbar="bold_italic_alignment_link"] iframe {
            height: 200px !important;
            min-height: 200px;
        }

        .acf-field .acf-editor-wrap[data-toolbar="bold_italic_alignment_link"] div.mce-statusbar{
            display: none;
        }



        </style>
        <?php
}


add_action('acf/input/admin_head', 'bold_alignment_height'); 
function bold_alignment_height() {
        ?>
        <style type="text/css">

        .acf-field .acf-editor-wrap[data-toolbar="bold_and_alignment"] iframe {
            height: 100px !important;
            min-height: 100px;
        }

        .acf-field .acf-editor-wrap[data-toolbar="bold_and_alignment"] div.mce-statusbar{
            display: none;
        }



        </style>
        <?php
}