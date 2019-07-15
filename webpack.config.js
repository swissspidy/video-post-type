/**
 * External dependencies
 */
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

/**
 * WordPress dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const postCssPlugins = process.env.NODE_ENV === 'production' ?
    [
        require( 'postcss-nested' ),
        require( 'autoprefixer' ),
        require( 'cssnano' )( {
            safe: true,
        } )
    ] :
    [
        require( 'postcss-nested' ),
        require( 'autoprefixer' ),
    ];

module.exports = {
	...defaultConfig,
	entry: {
		'editor': './assets/js/src/editor.js',
	},
	output: {
		path: __dirname + '/assets/js/',
		filename: '[name].js',
		library: 'VideoPostType',
		libraryTarget: 'this',
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.css$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
					},
					'css-loader',
					{
						loader: 'postcss-loader',
						options: {
							plugins: postCssPlugins,
						}
					},
				]
			},
		],
	},
	plugins: [
		...defaultConfig.plugins,
		new MiniCssExtractPlugin({
			filename: '../css/editor.css',
			chunkFilename: '[id].css'
		}),
	],
};
