{\rtf1\ansi\ansicpg1252\cocoartf1187\cocoasubrtf370
{\fonttbl\f0\froman\fcharset0 Times-Roman;}
{\colortbl;\red255\green255\blue255;}
\vieww10800\viewh8400\viewkind0
\deftab720
\pard\pardeftab720

\f0\fs24 \cf0 html->charset();?> html->style(array('bootstrap.min','bootstrap-responsive.min','app')); ?> html->script('head.js');?> scripts();?> html->link('Icon', null, array('type' => 'icon'));?> html->link('RSS-Feed', '/posts.rss', array('type' => 'rss')); ?> _render('element', 'navbar');?>\
_render('element', 'header');?>\
_render('element', 'sidebar');?>\
message->flash('Auth.message', array('class' => 'alert alert-info'));?> content();?>\
_render('element', 'footer');?>\
}