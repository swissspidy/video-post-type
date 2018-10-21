/**
 * WordPress dependencies
 */
import { PanelBody, TextControl } from '@wordpress/components';
import { compose, ifCondition } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { PluginPostStatusInfo } from '@wordpress/edit-post';
import { InspectorControls } from '@wordpress/editor';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export class PreviewToggle extends Component {
	render() {
		const {
				  meta: {
							_video_url: videoUrl,
						} = {},
				  updateMeta,
			  } = this.props;

		return (
			<InspectorControls>
				<PluginPostStatusInfo>
					<PanelBody title={__( 'Video', 'video-post-type' )}>
						<TextControl
							label={__( 'Video URL', 'video-post-type' )}
							help={__( 'For example. from a Vimeo or YouTube video.', 'video-post-type' )}
							value={videoUrl}
							onChange={( value ) => {
								updateMeta( { _video: value || '' } );
							}}
						/>
					</PanelBody>
				</PluginPostStatusInfo>
			</InspectorControls>
		);
	}
}

export default compose( [
	withSelect( ( select ) => {
		const {
				  getCurrentPostType,
				  getEditedPostAttribute,
			  } = select( 'core/editor' );

		return {
			postType: getCurrentPostType(),
			meta: getEditedPostAttribute( 'meta' ),
		};
	} ),
	withDispatch( ( dispatch, { meta } ) => {
		const { editPost } = dispatch( 'core/editor' );

		return {
			updateMeta( newMeta ) {
				// Important: Old and new meta need to be merged in a non-mutating way!
				editPost( { meta: { ...meta, ...newMeta } } );
			},
		};
	} ),
	ifCondition( ( { postType } ) => 'video' === postType ),
] )( PreviewToggle );
