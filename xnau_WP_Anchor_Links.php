<?php

/**
 * supplies anchor links to the WP linker
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2016  xnau webdesign
 * @license    GPL3
 * @version    0.1
 * @link       http://xnau.com/wordpress-plugins/
 * @depends    
 */
class xnau_WP_Anchor_Links {

  /**
   * @var string  the post content
   */
  private $post_content;

  /**
   * @var int the post ID
   */
  private $post_id;

  /**
   * @var array of content ids 
   */
  private $content_id_list;

  /**
   * sets up the class
   */
  private function __construct()
  {
    $this->setup_post();
  }

  /**
   * supplies a list of named anchors
   * 
   * @param string  $results
   * @return string
   */
  public static function get_content_anchors( $results )
  {
    $headings = new self();

    $anchor_title = __( 'Anchor' );

    error_log( __METHOD__ . ' id list: ' . print_r( $headings->content_id_list, 1 ) );

    foreach ( $headings->content_id_list as $id => $title ) {
      $results[] = array(
          'ID' => '',
          'title' => $title,
          'permalink' => '#' . $id,
          'info' => $anchor_title,
      );
    }

    return $results;
  }

  /**
   * gets the post's ID list from the transient
   */
  private function get_post_ids()
  {
    $all_post_ids = get_transient( xnau_WP_Headings_IDs::id_list_transient );
    $this->content_id_list = $all_post_ids[$this->post_id];
  }

  /**
   * gets the current post in an admin AJAX call
   * 
   * @return object
   */
  private function setup_post()
  {
    $url = parse_url( filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING ) );
    parse_str( $url['query'], $vars );
    $this->post_id = $vars['post'];
    $this->get_post_ids();
    //$this->post_content = get_post( $this->post_id );
  }

}
