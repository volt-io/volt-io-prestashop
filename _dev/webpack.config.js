const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require("terser-webpack-plugin");
const METADATA = "/**\n" +
	" * Volt Technologies Holdings Limited\n" +
	" *\n" +
	" * NOTICE OF LICENSE\n" +
	" *\n" +
	" * This source file is subject to the GNU Lesser General Public License\n" +
	" * that is bundled with this package in the file LICENSE.txt.\n" +
	" * It is also available through the world-wide-web at this URL:\n" +
	" * https://www.gnu.org/licenses/lgpl-3.0.en.html\n" +
	" *\n" +
	" * @package        Volt Technologies Holdings Limited\n" +
	" * @copyright      Copyright (c) 2015-2023\n" +
	" * @license        https://www.gnu.org/licenses/lgpl-3.0.en.html GNU Lesser General Public License\n" +
	" */";

let config = {
	target: ["web", "es5"],
	entry: {
		frontend: [
			'./frontend/js/front.ts',
			'./frontend/scss/front.scss',
		],
		backend: [
			'./backend/js/admin.ts',
			'./backend/scss/admin.scss',
		],
	},
	output: {
		path: path.resolve(__dirname, '../views/js'),
		filename: '[name].min.js',
	},
	module: {
		rules: [
			{
				test: /\.ts?$/,
				loader: 'esbuild-loader',
				options: {
					loader: 'ts',
					target: 'es2015'
				}
			},
			{
				test: /\.js$/,
				loader: 'esbuild-loader',
				options: {
					loader: 'js',
					target: 'es2015'
				}
			},
			{
				test: /\.js/,
				loader: 'esbuild-loader',
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'sass-loader',
				],
			},
			{
				test: /.(png|woff(2)?|eot|otf|ttf|svg|gif)(\?[a-z0-9=\.]+)?$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '../css/[hash].[ext]',
						},
					},
				],
			},
			{
				test: /\.css$/,
				use: [MiniCssExtractPlugin.loader, 'style-loader', 'css-loader'],
			},
		],
	},
	resolve: {
		extensions: ['.ts', '.tsx', '.js']
	},
	plugins: [
		new MiniCssExtractPlugin({filename: path.join('..', 'css', '[name].css')}),
		new webpack.BannerPlugin({
		 banner: METADATA,
		 raw: true,
		 entryOnly: true,
		}),
	]
};

if (process.env.NODE_ENV === 'production') {
	config.optimization = {
		minimizer: [
			new TerserPlugin(

					{
						minify: TerserPlugin.uglifyJsMinify,
						terserOptions: {
							format: {
								comments: false,
							},
						},
						extractComments: "all",



				//
				// sourceMap: false,
				// extractComments: false,
				// uglifyOptions: {
				// 	compress: {
				// 		sequences: true,
				// 		conditionals: true,
				// 		booleans: true,
				// 		if_return: true,
				// 		join_vars: true,
				// 		drop_console: true,
				// 	},
				// 	output: {
				// 		beautify: false,
				// 		comments: false,
				// 		// comments: 'some',
				// 		// preamble: METADATA,
				// 	},
				// 	mangle: { // see https://github.com/mishoo/UglifyJS2#mangle-options
				// 		keep_fnames: false,
				// 		toplevel: true,
				// 	},
				// }
				}
			)
		]
	}
} else {
	config.optimization = {
		minimizer: [
			new TerserPlugin({
				terserOptions: {
					format: {
						comments: false,
					},
				},
				extractComments: "all",

			})
		]
	}
}
//
config.mode = 'development';
// config.mode = 'production';

module.exports = config;
