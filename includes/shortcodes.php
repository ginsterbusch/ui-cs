<?php
/**
 * Shortcodes
 */
class _ui_cs_Shortcodes extends _ui_cs_Base {
	function __construct( $widgets = array() ) {
		//new __debug( 'fires!', __CLASS__ );
		
		
		$this->init_shortcodes();
		$this->add_shortcodes_to_widgets( $widgets );
		//$this->add_shortcodes_to_acf();
	}
	
	public static function init() {
		new self();
	}
	
	function add_shortcodes_to_widgets( $known_widgets = array() ) {
		if( empty( $known_widgets ) ) {
			$known_widgets = array(
				'text_widget',
				'widget_text_content',
			);
		}
		
		if( !empty( $known_widgets ) ) {
			foreach( $known_widgets as $strFilterHook ) {
				add_filter( $strFilterHook, 'do_shortcode' );
			}
		}
		
	}
	
	function init_shortcodes() {
		$arrMethods = $this->_get_methods_by( 'prefix', 'shortcode_' );
		
		//new __debug( $arrMethods, __METHOD__ );
		
		if( !empty( $arrMethods ) ) {
			foreach( $arrMethods as $strMethod ) {
				
				add_shortcode( str_replace( 'shortcode_', '', $strMethod ), array( $this, $strMethod ) );
			}
		}
	}
	
	function _get_methods_by( $type = 'prefix', $value = '' ) {
		$return = false;
		
		if( !empty( $value ) ) {
		
			$methods = get_class_methods( $this );
			
			foreach( $methods as $strMethod ) {
				switch( $type ) {
					
					case 'suffix':
						if( strrpos( $strMethod, $value ) !== false ) {
							$arrFoundMethods[] = $strMethod;
						}
						break;
					case 'prefix':
					default:
						if( strpos( $strMethod, $value ) !== false ) {
							$arrFoundMethods[] = $strMethod;
						}
						break;
				}
			}
			
			if( !empty( $arrFoundMethods ) ) {
				$return = $arrFoundMethods;
			}
		}
		
		return $return;
	}
	
	function shortcode_hide_content( $attr = array(), $content = '' ) {
		$return = $content;
	
		$params = shortcode_atts( array(
			'label' => 'removed content: %d characters',
		), $attr );
		
		
		if( !empty( $params['label'] ) ) {
			$return = sprintf( $params['label'], strlen( $content ) ) . "\n" . $return;
		}
		
		$return = '<!-- ' . $return . ' -->';
		
		return $return;
	}
	
	function shortcode_remove_content( $attr = array(), $content = '' ) {
		$return = '';
		$strLabel = '';
		
		$params = shortcode_atts( array(
			'use_comments' => false,
			'label' => 'removed content: %d characters',
		), $attr );
		
		////new __debug( array( 'params' => $params, 'attr' => $attr ), __METHOD__ );
		
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		if( !empty( $label ) ) {
			$strLabel = $label;
		}
		
		if( !empty( $use_comments ) ) {
			$return = '<!-- ' . $content . ' -->';
		} else {
			if( !empty( $strLabel ) ) {
				$return = '<!-- ' . sprintf( $strLabel, strlen( $content ) ) . ' -->';
			}
		}
		
		return $return . '<!-- passed through ' . __METHOD__ . ' -->';
	}
	
	function shortcode_permalink( $attr = array(), $content = '' ) {
		$return = '';
		
		$post = false;
		
		$params = shortcode_atts( array(
			'id' => 0,
			'post_id' => 0,
			'slug' => '',
			'post_type' => 'page',
		), $attr );
		
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		if( !empty( $slug ) && !empty( $post_type ) ) {
			$post = $this->get_post_by( 'slug', $slug, $post_type );
			
			if( !empty( $id ) ) {
				unset( $id );
			}
			
			$post_id = $post->ID;
		}
		
		if( !empty ( $id ) || !empty( $post_id ) ) {
			$post_id = ( !empty( $post_id ) ? $post_id : $id );
		}
		
		if( !empty( $post_id ) ) {
			$permalink = get_permalink( $post_id );
			
			if( !empty( $permalink ) ) {
				$return = $permalink;
			}
		}
		
		return $return;
	}
	
	function shortcode_link( $attr = array(), $content = '' ) {
		$return = '';
		
		if( !empty( $content ) ) {
			
			$params = shortcode_atts( array(
				'id' => 0,
				'post_id' => 0,
				'slug' => '',
				'post_type' => 'page',
				'class' => 'permalink',
				'query' => '',
			), $attr );
			
			if( !empty( $params ) ) {
				extract( $params, EXTR_SKIP );
				$url = $this->shortcode_permalink( $params );
			}
			
			if( !empty( $url ) ) {
				if( !empty( $query ) ) {
					$url .= '?' . str_replace('[amp]', '&amp;', $query );
				}
				
				$return = '<a href="' . $url . '"';
				
				if( !empty( $class ) ) {
					if( !empty( $post_type ) && strpos( $class, $post_type ) === false ) {
						$class .= ' type-'. $post_type;
					}
					
					$return .= ' class="' . $class . '"';
				}
				
				$return .= '>' . $content . '</a>';
			}
		}
		
		return $return;
	}
		
	function shortcode_site_url( $attr = array(), $content = '' ) {
		return get_site_url();
	}
	

	/**
	 * CSS Columns, inline style version
	 * 
	 * Also @see https://css-tricks.com/guide-responsive-friendly-css-columns/
	 */
	
	function shortcode_css_cols( $attr = array(), $content = '' ) {
		$return = $content;
		
		$params = shortcode_atts( array(
			'number' => 3, /** alias */
			'cols' => 3,
			'width' => 0,
			'wrap_tag' => 'div'
		), $attr );
		
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		if( !empty( $content ) ) {
			if( !empty( $number ) || !empty( $cols ) ) {
				$col_num = ( !empty( $number ) ? $number : $cols );
			}
			
			if( !empty( $col_num ) ) {
				$custom_style = '-webkit-column-count: ' . $col_num . '; -moz-column-count: ' . $col_num . '; column-count: ' . $col_num . ';';
			}
			
			if( !empty( $width ) ) {
				$custom_style .= '-webkit-column-width: ' . $col_num . '; -moz-column-width: ' . $col_num . '; column-width: ' . $col_num . ';'; 
			}
			
			if( empty( $wrap_tag ) ) {
				$wrap_tag = 'div';
			}
			
			$return = '<' . $wrap_tag . ' class="css-columns custom-style"';
			
			if( !empty( $custom_style ) ) {
				$return .= ' style="' . $custom_style . '"';
			}
			
			$return .= '>' . $content . '</' . $wrap_tag . '>';
		}
		
		return $return;
		
	}
	
	/**
	 * CSS columns, container class version
	 */
	
	function shortcode_columns( $attr = array(), $content = '' ) {
		$return = $content;
		
		$params = shortcode_atts( array(
			'number' => 3,
			'cols' => 3,
		), $attr );
		
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		if( !empty( $content ) ) {
			if( !empty( $number ) || !empty( $cols ) ) {
				$col_num = ( !empty( $number ) ? $number : $cols );
			}
			
			$return = '<div class="css-columns cols-' . $col_num . '">' . $content . '</div>';
		}
		
		
		
		return $return;
	}
	
	function shortcode_quote( $attr = array(), $content = '' ) {
		$return = $content;
		$strCite = $strAuthor = $strContent = '';
		
		$params = shortcode_atts( array(
			'cite' => '',
			'author' => '',
			'content_class' => 'quote-content',
			'author_class' => 'quote-author',
			'dash_class' => 'quote-dash',
		), $attr );
	
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		
		if( !empty( $content ) ) {
			$strContent = $content;
			
			$strContentClass = !empty( $content_class ) ? sanitize_html_class( $content_class ) : '';
			$strAuthorClass = !empty( $author_class ) ? sanitize_html_class( $author_class ) : '';
			$strDashClass = !empty( $dash_class ) ? sanitize_html_class( $dash_class ) : '';
			
			if( !empty( $cite ) || !empty( $author ) ) {
				$strAuthor = ( !empty( $cite ) ? $cite : $author );
				
				$strCite = ' cite="' . $strAuthor . '"';
				$strContent = "<em class=\"$strContentClass\">$content</em><br />\n<span class=\"$strAuthorClass\"><span class=\"$strDashClass\">&mdash; </span>$strAuthor</span>";
			}

			
			$return = sprintf( '<blockquote %s>%s</blockquote>', $strCite, $strContent );
		}
		
		return $return;
	}
	
	function shortcode_date( $attr = array(), $content = '' ) {
		$return = '';
		
		$params = shortcode_atts( array(
			'format' => 'Y-m-d H:i',
			'timestamp' => time(),
		), $attr );
	
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		if( !empty( $format ) ) {
			if( is_numeric( $timestamp ) ) {
				$timestamp = intval( $timestamp );
			}
			
			if( empty( $timestamp ) || !is_numeric( $timestamp ) ) {
				$timestamp = time();
			}
			
			$return = date( $format, $timestamp );
		}
		
		
		return $return;
	}
	
	function shortcode_button( $attr = array(), $content = '' ) {
		$return = '';
		
		$params = shortcode_atts( array(
			'url' => '',
			'id' => 0,
			'post_id' => 0,
			'slug' => '',
			'type' => 'primary',
			'class' => '',
			'post_type' => 'page',
		), $attr );
	
		if( !empty( $params ) ) {
			extract( $params, EXTR_SKIP );
		}
		
		if( empty( $url ) ) {
		
			if( !empty( $id ) || !empty( $post_id ) ) {
				$post_id = ( !empty( $id ) ? $id : $post_id );
			} elseif( !empty( $slug ) ) {
				$post_id = $this->get_post_by( 'slug', $slug, $post_type );
			}
			
			if( !empty( $post_id ) && absint( $post_id ) > 0 ) {
				$url = get_permalink( $post_id );
			}
		}
		
		if( !empty( $url ) ) {
			$arrClass = array( 'btn' );
			if( !empty( $type ) ) {
				$arrClass[] = 'btn-' . $type;
			}
			
			if( !empty( $class ) ) {
				$arrHTMLClass = ( strpos( $class, ' ' ) !== false ? explode(' ', $class ) : array( $class ) );
				
				$arrClass = array_unique( array_filter( array_merge( $arrClass, $arrHTMLClass ) ) );
			}
			
			if( !empty( $arrClass ) ) {
				$strClass = implode(' ', $arrClass );
			}
			
			$return = '<a href="' . $url . '" class="'.$strClass.'">' . $content . '</a>';
		}
		
		return $return;
	}
	
	/**
	 * Replacement for The Gallery shortcode using Timthumb.
	 *
	 * This implements the functionality of the Gallery Shortcode for displaying
	 * WordPress images on a post.
	 *
	 * @since 2.5.0
	 *
	 * @param array $attr Attributes of the shortcode.
	 * @return string HTML content to display gallery.
	 */
	function shortcode_wp_gallery($attr = array(), $content = '' ) {
		/**
		 * No triggers found => Pass the handling to the original shortcode function
		 */
		if( empty( $attr['width'] ) ) {
			return gallery_shortcode( $attr );
		}
		
		$post = get_post();

		/**
		 * Works identical to @param $ids, but overrides it
		 */
		
		if( !empty( $attr['slugs'] ) ) {
			// retrieve all slugs
			if( ! is_array( $attr['slugs'] ) ) {
				$arrSlugs = array( $attr['slugs'] );
				
				if( strpos( $attr['slugs'], ',' ) !== false ) {
					$arrSlugs = explode( ',', $attr['slugs'] );
				}
				
			} else {
				$arrSlugs = $attr['slugs'];
			}
			
			foreach( $arrSlugs as $strPostSlug ) {
				$slugItem = $this->get_post_by( 'slug', trim( $strPostSlug ) );
				
				if( !empty( $slugItem ) ) {
					$arrIDs[] = $slugItem->ID;
				}
			}
			
			if( !empty( $arrIDs ) ) {
				$attr['ids'] = implode(',', $arrIDs );
			}
		}

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}
		
	

		/**
		 * Filters the default gallery shortcode output.
		 *
		 * If the filtered output isn't empty, it will be used instead of generating
		 * the default gallery template.
		 *
		 * @since 2.5.0
		 * @since 4.2.0 The `$instance` parameter was added.
		 *
		 * @see gallery_shortcode()
		 *
		 * @param string $output   The gallery output. Default empty.
		 * @param array  $attr     Attributes of the gallery shortcode.
		 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
		 */
		/*
		$output = apply_filters( 'post_gallery', '', $attr, $instance );
		if ( $output != '' ) {
			return $output;
		}*/

		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => $html5 ? 'figure'     : 'dl',
			'icontag'    => $html5 ? 'div'        : 'dt',
			'captiontag' => $html5 ? 'figcaption' : 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
			'link'       => '',
			'width' 	 => 0,
			'height' 	 => 0,
		), $attr, 'gallery' );
		
		
		// try fetching an appropriate size setting based on width, optionally on height, too
		if( !empty( $width ) ) {
			// fetch available sizes
			$arrImageSizes = $this->get_image_sizes();
			
			// retrieve anything with the width <= current width
			foreach( $arrImageSizes as $strSize => $arrSizeAtts ) {
				if( $arrSizeAtts['width'] <= $width ) {
					$arrPossibleSizes[ $strSize ] = $arrSizeAtts;
					$arrWidths[ $strSize ] = $arrSizeAtts[ 'width'];
					
					if( !empty( $height ) && $arrSizeAtts['height'] > $height ) {
						unset( $arrPossibleSizes[ $strSize ] );
						unset( $arrWidths[ $strSize ] );
					}
						
				}
			}
			
			if( !empty( $arrPossibleSizes ) ) {
				// sort widths
				natsort( $arrWidths );
				
				$arrRWidths = array_reverse( $arrWidths, true );
				$c = 0;
				
				$strAppropiateSize = '';
				foreach( $arrRWidths as $strSize => $iWidth ) {
					if( $c == 0 ) {
						$strAppropiateSize = $strSize;
						break;
					}
					
					$c++;
				}
				// eg. width = 200, height = 150 .. 
				
				if( !empty( $strAppropiateSize ) ) {
					$atts['size'] = $strAppropiateSize;
				}
			}
		}
		

		return gallery_shortcode( $atts );
	}
	
	/**
	 * Get size information for all currently-registered image sizes.
	 *
	 * @global $_wp_additional_image_sizes
	 * @uses   get_intermediate_image_sizes()
	 * @return array $sizes Data for all currently-registered image sizes.
	 */
	function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$return = false;

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}
		
		if( !empty( $sizes ) ) {
			$return = $sizes;
		}

		return $return;
	}

	/**
	 * Get size information for a specific image size.
	 *
	 * @uses   get_image_sizes()
	 * @param  string $size The image size for which to retrieve data.
	 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
	 */
	function get_image_size( $size = '' ) {
		$return = false;
		
		if( !empty( $size ) ) {
			$sizes = $this->get_image_sizes();

			if ( isset( $sizes[ $size ] ) ) {
				$return = $sizes[ $size ];
			}
		}

		return $return;
	}

}
