import { registerPlugin } from '@wordpress/plugins';
import { default as VideoPanel } from './components/video-panel';

registerPlugin(
	'video-post-type',
	{
		icon: 'video-alt2',
		render: VideoPanel,
	}
);
