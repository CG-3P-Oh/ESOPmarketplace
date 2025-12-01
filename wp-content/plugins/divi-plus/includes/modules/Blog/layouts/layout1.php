<?php
/**
 * The Template for displaying Layout 1
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/blog/layout1.php
 *
 * HOWEVER, on occasion divi-plus will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 *
 * @author      Elicus Technologies <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.0.0
 */

$allowed_tags = array(
	'div' => array(
    	'class' => true,
        'id' => true,
    ),
	'p' => array(
    	'class' => true,
        'id' => true,
    ),
	'h1' => array(
    	'class' => true,
        'id' => true,
    ),
	'h2' => array(
    	'class' => true,
        'id' => true,
    ),
	'h3' => array(
    	'class' => true,
        'id' => true,
    ),
	'h4' => array(
    	'class' => true,
        'id' => true,
    ),
	'h5' => array(
    	'class' => true,
        'id' => true,
    ),
	'h6' => array(
    	'class' => true,
        'id' => true,
    ),
	'ul' => array(
    	'class' => true,
        'id' => true,
    ),
	'ol' => array(
    	'class' => true,
        'id' => true,
    ),
	'li' => array(
    	'class' => true,
        'id' => true,
    ),
	'a' => array(
    	'href'  => true,
        'title' => true,
        'class' => true,
        'id' => true,
        'target' => true,
        'rel' => true,
    ),
    'abbr' => array(
        'title' => true,
    ),
    'acronym' => array(
        'title' => true,
    ),
    'b' => array(),
    'blockquote' => array(
        'cite' => true,
    ),
    'cite' => array(),
    'code' => array(),
    'del'  => array(
        'datetime' => true,
    ),
    'em' => array(),
    'i' => array(),
    'q' => array(
        'cite' => true,
    ),
    's' => array(),
    'strike' => array(),
    'strong' => array(),
    'span' => array(
    	'class'  => true,
        'id' => true,
    ),
    'img' => array(
    	'class' => true,
    	'id' => true,
    	'width' => true,
    	'height' => true,
    	'src' => true,
    	'srcset' => true,
    ),
);

$output .= '<article id="post-' . $post_id . '" class="' . $post_classes . '" >';

// Post Featured Image Wrapper.
if ( '' !== $thumb && 'on' === $show_thumbnail ) {
	$output .= '<div class="dipl_blog_post_image_wrapper">';
	$output .= '<a href="' . esc_url( get_the_permalink( $post_id ) ) . '" class="dipl_blog_post_image_link">';
	$output .= et_core_intentionally_unescaped( $thumb, 'html' );
	$output .= '</a>';
	$output .= '</div> <!-- dipl_blog_post_image_wrapper -->';
}

// Post Content Wrapper.
$output .= '<div class="dipl_blog_post_content_wrapper">';
$output .= '<div class="dipl_blog_post_content_inner_wrap">';

if ( 'on' === $show_categories ) {
	$categories = get_the_category_list( ' ', 'empty_string', $post_id );
	$output    .= sprintf(
		'<div class="dipl_blog_post_categories">%1$s</div>',
		$categories
	);
}

// Post Title.
$output .= '<' . esc_html( $processed_title_level ) . ' class="dipl_blog_post_title"><a href="' . esc_url( get_the_permalink( $post_id ) ) . '">' . wp_kses( get_the_title( $post_id ), $allowed_tags ) . '</a></' . esc_html( $processed_title_level ) . '>';

// Post Excerpt or Content.
if ( 'on' === $show_content ) {
	$post_content = get_the_content( null, false, $post_id );
	global $more;

	// page builder doesn't support more tag, so display the_content() in case of post made with page builder.
	if ( et_pb_is_pagebuilder_used( $post_id ) ) {
        //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$more    = 1;
		$content = et_core_intentionally_unescaped( do_shortcode( $post_content ), 'html' );
		if ( '' !== $content ) {
			$output .= '<div class="dipl_blog_post_content">' . $content . '</div>';
		}
	} else {
        //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$more    = null;
		$content = wp_kses_post( dipl_strip_shortcodes( apply_filters( 'the_content', $post_content ) ) );
		if ( '' !== $content ) {
			$output .= '<div class="dipl_blog_post_content">' . $content . '</div>';
		}
	}
} else {
	if ( has_excerpt() && '' !== trim( get_the_excerpt( $post_id ) ) && 0 !== intval( $excerpt_length ) ) {
		$excerpt = wpautop( dipl_strip_shortcodes( get_the_excerpt( $post_id ) ) );
		if ( '' !== $excerpt ) {
			$output .= '<div class="dipl_blog_post_content">' . $excerpt . '</div>';
		}
	} else {
		if ( 0 !== intval( $excerpt_length ) ) {
			$excerpt = wpautop( strip_shortcodes( dipl_truncate_post( $excerpt_length, false, $post_id, true ) ) );
			if ( '' !== $excerpt ) {
				$output .= '<div class="dipl_blog_post_content">' . $excerpt . '</div>';
			}
		}
	}
	if ( 'on' === $show_read_more ) {
		$read_more = sprintf(
			'<div class="dipl_blog_post_read_more_link">%1$s</div>',
			et_core_intentionally_unescaped( $read_more_button, 'html' )
		);
		$output   .= et_core_intentionally_unescaped( $read_more, 'html' );
	}
}

$output .= '</div> <!-- dipl_blog_post_content_inner_wrap -->';

// Post Meta.
if ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_comments ) {
	
	$output    .= sprintf(
		'<p class="dipl_blog_post_meta">%1$s %2$s %3$s</p>',
		(
			'on' === $show_author ?
			et_core_intentionally_unescaped(
				sprintf(
					'<span class="dipl_blog_post_author"><span class="dipl_blog_post_author_icon et-pb-icon">&#xe08a;</span>%s</span>',
					get_the_author_posts_link()
				),
				'html'
			) :
			''
		),
		(
			'on' === $show_date ?
			et_get_safe_localization(
				sprintf(
					'<span class="dipl_blog_post_published"><span class="dipl_blog_post_published_icon et-pb-icon">&#xe023;</span>%s</span>',
					esc_html( get_the_date( $post_date, $post_id ) )
				)
			) :
			''
		),
		(
			'on' === $show_comments ?
			et_get_safe_localization(
				sprintf(
					'<span class="dipl_blog_post_comments"><span class="dipl_blog_post_comments_icon et-pb-icon">&#xe066;</span>%s</span>',
					sprintf(
						// translators: %s: comment.
						esc_html( _nx( '%s Comment', '%s Comments', get_comments_number( $post_id ), 'number of comments', 'divi-plus' ) ),
						number_format_i18n( get_comments_number( $post_id ) )
					)
				)
			) :
			''
		)
	);
}

$output .= '</div> <!-- dipl_blog_post_content_wrapper -->';

$output .= '</article>';
