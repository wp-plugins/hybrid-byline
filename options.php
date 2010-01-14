<?php
/**
* Handles the author output
*
* @since 0.1
*/
function hbp_author(){
	global $hbp_settings;
	
	if ( $hbp_settings['byline_post_show_authorlink'] == 1 ) {			
		$author = sprintf(
			'<span class="byline-prep byline-prep-author text">By</span> <span class="author vcard"><a href="%1$s" title="%2$s" class="url fn n">%3$s</a></span></span>',
			get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
			esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ),
			get_the_author()
		);
	} else {
		$author = '<span class="fn n">';
		$author .= get_the_author();
		$author .= '</span>';
	}
	
	return $author;
}

/**
* Handles the date output
*
* @since 0.1
*/
function hbp_date() {
	global $hbp_settings;
	
	$date .= ' <span class="byline-prep byline-prep-published text">on</span> ';
	$date .= '<abbr class="published" title="' . sprintf( get_the_time( __('l, F jS, Y, g:i a', 'hybrid') ) ) . '">' . sprintf( get_the_time( get_option( 'date_format' ) ) ) . '</abbr>';	

	return $date;
}

/**
* Handles the nofollow 
*
* @since 0.1
*/
function hbp_nofollow() {
	global $hbp_settings;
	
	if ( $hbp_settings['byline_post_authorlink_nofollow'] == 0 ) {
		$nofollow = 'rel="nofollow"';
	}

	return $nofollow;
}

/**
* Handles the comment output
*
* @since 0.1
*/
function hbp_comments(){
	$num_comments = get_comments_number();
	$text = '  -  ';
	$text .= (comments_open()) ? '<a href="' . get_permalink() . '#comments" rel="nofollow">' . $num_comments . ' Comments</a>' : __('Comments on this entry are closed', 'thesis');
	return apply_filters('thesis_comments_link', $text);
}

/**
* Handles the categories output
*
* @since 0.1
*/
function hbp_categories(){
	$categories = ' <span class="categories">in ';
	$categories .= get_the_category_list(',');
	$categories .= '</span>';
	
	return $categories;
}
?>