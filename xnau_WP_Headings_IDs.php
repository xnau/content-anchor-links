<?php

/**
 * adds unique IDs to content headings
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2016  xnau webdesign
 * @license    GPL3
 * @version    0.3
 * @link       https://xnau.com/content-anchor-links/
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
   * @var int the current post ID
   */
  private $post_id;

  /**
   * @var int max length of the id
   */
  const id_length = 30;
  
  /**
   * @var string name of the id list transient
   */
  const id_list_transient = 'content-anchor-links';

  /**
   * @param string $content the incoming content
   * @patam int $post_id
   */
  private function __construct( $content, $post_id )
  {
    $this->content = $content;
    $this->post_id = $post_id;
    $this->element_id_list();
  }

  /**
   * supplies the content with heading ID attributes added
   * 
   * @param string  $content
   * @patam int $post_id
   * @return string modified content
   */
  public static function add_heading_ids( $content, $post_id )
  {
    $headings = new self( $content, $post_id );
    return $headings->add_anchors_to_headings();
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
    $pattern = '%<(?<tag>h[2-3])(?!.+id=".+)(?<atts>[^>]*)>(?<content>.+)</\1>%';

    // now run the pattern and callback function on content
    // and process it through a function that replaces the title with an id 
    $content = preg_replace_callback( $pattern, array($this, 'place_ids'), $this->content );
    
    // store the assembled ist of ids to the transient
    $this->update_transient();
    
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
    $title = strip_tags( $matches['content'] );
    $slug = $this->unique_id( $this->make_slug( $title ) );
    
    // add the new slug to the id list
    $this->add_id_to_list( $title, $slug );
    
    return '<' . $matches['tag'] . ' id="' . $slug . '" ' . $matches['atts'] . '>' . $matches['content'] . '</' . $matches['tag'] . '>';
  }
  
  /**
   * makes a slug out of a title
   * 
   * @param string $title the title or content of the element
   * 
   * @return string the derived slug
   */
  private function make_slug( $title )
  {
    if ( empty( $title ) ) {
      $title = uniqid('anchor-');
    }
    return substr( sanitize_title_with_dashes( $title ), 0, self::id_length );
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
    return in_array( $slug, array_keys( $this->content_id_list ) );
  }

  /**
   * provides an array of all the element IDs in a block of content
   * 
   * @return array  of found IDs
   */
  private function element_id_list()
  {
    preg_match_all( '%<(?<tag>.+?) id="(?<id>.*?)".*(?:>(?<title>.*)</\1|/>)%', $this->content, $matches );
    
    foreach ( array_keys( $matches['tag'] ) as $index ) {
      $this->content_id_list[ $matches['id'][$index] ] = ( empty( $matches['title'][$index] ) ? $matches['id'][$index] . ' (' . $matches['tag'][$index] . ')' : $matches['title'][$index] );
    }
  }
  
  /**
   * adds a new ID to the list
   * 
   * @param string  $title
   * @param string $slug the new id
   */
  private function add_id_to_list( $title, $slug )
  {
    $this->content_id_list[$slug] = $title;
  }
  
  /**
   * updates the transient
   * 
   */
  private function update_transient()
  {
    $all_post_id_lists = get_transient(self::id_list_transient);
    $all_post_id_lists[ $this->post_id ] = $this->content_id_list;
    set_transient(self::id_list_transient, $all_post_id_lists, DAY_IN_SECONDS);
  }

}
