/**
 * External Dependencies
 */
const path = require("path");

/**
* WordPress Dependencies
*/
const defaultConfig = require("@wordpress/scripts/config/webpack.config.js");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
module.exports = {
    ...defaultConfig,
    entry: {
        index: path.resolve(__dirname, "src", "index.js"),
    },
    output: {
        path: path.resolve(__dirname, "build/"),
        filename: "[name].min.js"
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].min.css"
        }),
    ]
}