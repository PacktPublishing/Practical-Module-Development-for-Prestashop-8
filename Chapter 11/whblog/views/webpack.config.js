/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');

let config = {
    entry: {
        WHBlogCategory: [
            './js/WHBlogCategory',
        ]
    },
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: '[name].bundle.js'
    },
    //devtool: 'source-map', // uncomment me to build source maps (really slow)
    resolve: {
        extensions: ['.js', '.ts'],
        alias: {
          '@PSTypes': path.resolve(__dirname, '../../../admin-dev/themes/new-theme/js/types'),
          '@components': path.resolve(__dirname, '../../../admin-dev/themes/new-theme/js/components'),
          '@app': path.resolve(__dirname, '../../../admin-dev/themes/new-theme/js/app')
        }
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                include: path.resolve(__dirname, '../js'),
                use: [{
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            ['es2015', { modules: false }]
                        ]
                    }
                }]
            },
            {
              test: /\.ts?$/,
              loader: 'ts-loader',
              options: {
                onlyCompileBundledFiles: true,
              },
              exclude: /node_modules/,
            },
        ]
    },
    plugins: []
};

if (process.env.NODE_ENV === 'production') {
    config = {
      ...config,
      optimization: {
        minimize: true,
        minimizer: [
          new TerserPlugin(),
        ],
      },
    }
} else {
    config.plugins.push(new webpack.HotModuleReplacementPlugin());
}

module.exports = config;
