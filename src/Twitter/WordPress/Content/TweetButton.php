<?php
/*
The MIT License (MIT)

Copyright (c) 2015 Twitter Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

namespace Twitter\WordPress\Content;

/**
 * Add a Tweet button to post content
 *
 * @since 1.0.0
 */
class TweetButton
{

	/**
	 * Get the stored site option for Tweet button content
	 *
	 * @since 1.0.0
	 *
	 * @return array stored options {
	 *   @type string option name
	 *   @type string option value
	 * }
	 */
	protected static function getOption()
	{
		return get_option( \Twitter\WordPress\Admin\Settings\TweetButton::OPTION_NAME, array() );
	}

	/**
	 * Filter the_content, possibly adding Tweet button(s)
	 *
	 * @since 1.0.0
	 *
	 * @param string $content content of the current post
	 *
	 * @return string $content content of the current post, possibly with Tweet button markup added
	 */
	public static function contentFilter( $content )
	{
		$options = static::getOption();
		if ( isset( $options['position'] ) ) {
			$position = $options['position'];
			unset( $options['position'] );

			$tweet_button = \Twitter\WordPress\Shortcodes\Share::shortcodeHandler( $options );
			if ( $tweet_button ) {
				// wrap in newlines to preserve content scanners looking for adjacent content on its own line
				$tweet_button = "\n" . $tweet_button . "\n";
				if ( 'before' === $position ) {
					return $tweet_button . $content;
				} else if ( 'after' === $position ) {
					return $content . $tweet_button;
				} else if ( 'both' === $position ) {
					return $tweet_button . $content . $tweet_button;
				}
			}
		}

		return $content;
	}
}
