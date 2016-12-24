<?php

/**
 * adds unique IDs to content headings
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
class xnau_WP_Headings_IDs {

  /**
   * @var array of existing IDs
   */
  private $content_id_list;

  /**
   * @var string the current content
   */
  private $content;

  /**
   * @var int max length of the id
   */
  const id_length = 30;

  /**
   * @param string $content the incoming content
   */
  private function __construct( $content )
  {
    $this->content = $content;
    $this->element_id_list();
  }

  /**
   * supplies the content with heading ID attributes added
   * 
   * @param string  $content
   * @return string modified content
   */
  public static function add_heading_ids( $content )
  {
    $headings = new self( $content );
    return $headings->add_anchors_to_headings();
  }
  
  /**
   * supplies a list of named anchors
   * 
   * @param string  $results
   * @return string
   */
  public static function get_content_anchors ( $results )
  {
    $headings = new self( self::post()->post_content );
    
    $anchor_title = __('Anchor');
    
    foreach( $headings->content_id_list as $id ) {
      $results[] = array(
          'ID' => '',
          'title' => $id,
          'permalink' => '#' . $id,
          'info' => $anchor_title,
      );
    }
    return $results;
  }
  
  /**
   * gets the current post in an admin AJAX call
   * 
   * @return object
   */
  private static function post()
  {
    $url = parse_url( filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING ) );
    parse_str( $url['query'], $vars );
    return get_post( $vars['post'] );
  }

  /**
   * adds ids to headers in the content
   * 
   * this adds anchors to all h2, h3 headings, can be expanded to others if needed
   * 
   * @return string the modified content
   */
  private function add_anchors_to_headings()
  {
    // pattern to select all headings without ids
    $pattern = '%<h([2-3])(?!.+id=".+)(?<atts>.*)>(?<heading>.*?)</h\1>%';

    // now run the pattern and callback function on content
    // and process it through a function that replaces the title with an id 
    $content = preg_replace_callback( $pattern, array($this, 'place_ids'), $this->content );
    
    return $content;
  }

  /**
   * places the ID in the heading
   * 
   * @param array $matches the match array
   * @return string the new heading
   */
  public function place_ids( $matches )
  {
    $title = $matches['heading'];
    $slug = $this->unique_id( substr( sanitize_title_with_dashes( $title ), 0, self::id_length ) );
    return '<h2 id="' . $slug . '" ' . $matches['atts'] . '>' . $title . '</h2>';
  }

  /**
   * prepares a unique id string
   * 
   * @param string $title the title string
   * @param array $id_list list of existing ids
   * 
   * @return string the unique ID
   */
  private function unique_id( $slug )
  {
    $check = 0;
    $base_slug = $slug;
    while ( $this->is_not_unique( $slug ) && $check < 50 ) {
      $slug = $base_slug . '-' . $check;
      $check++;
    }
    // add the new slug to the id list
    $this->content_id_list[] = $slug;
    return $slug;
  }

  /**
   * checks the slug for uniqueness
   * 
   * @param string $slug the string to test
   * @return bool true if the string is not unique
   */
  private function is_not_unique( $slug )
  {
    return in_array( $slug, $this->content_id_list );
  }

  /**
   * provides an array of all the element IDs in a block of content
   * 
   * @return array  of found IDs
   */
  private function element_id_list()
  {
    $count = preg_match_all( '/id="(.*)"/', $this->content, $matches );
    $this->content_id_list = $matches[1];
  }

}
