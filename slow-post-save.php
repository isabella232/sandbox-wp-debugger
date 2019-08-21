<?php

class SWPD_Slow_Post_Save extends SlowQueries {

	public $debugger_name = 'slow post save';

	public function __construct() {
		if ( false === defined( 'SAVEQUERIES' ) ) {
			define( 'SAVEQUERIES', true );
		}
		add_filter( 'wp_redirect', array( $this, 'wp_redirect_filter' ), -99999, 1 );
	}

	public function is_post_save() {
		$is_post_save = false;
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $is_post_save;
		}
		if ( true === isset( $_POST['action'] ) && 'editpost' === $_POST['action'] ) {
			$is_post_save = true;
		}
		return $is_post_save;
	}

	public function wp_redirect_filter( $location ) {
		if ( true === $this->is_post_save() ) {
			$this->log();
		}
		return $location;
	}

	public function get_post_id() {
		if ( isset( $_GET['post'] ) ) {
			$post_id = $post_ID = (int) $_GET['post'];
		} elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = $post_ID = (int) $_POST['post_ID'];
		} else {
			$post_id = $post_ID = 0;
		}
		return $post_id;
	}
}

new SWPD_Slow_Post_Save();
