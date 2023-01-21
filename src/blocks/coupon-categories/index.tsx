/**
 * External dependencies
 */
const { createBlock, registerBlockType } = wp.blocks;
import { Icon, listView } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import "./editor.scss";
import metadata from "./block.json";
import "./style.scss";
import { Edit } from "./edit";
registerBlockType(metadata, {
	icon: {
		src: (
			<Icon
				icon={ listView }
				className="zior-block-editor-components-block-icon"
			/>
		),
	},
	edit: Edit,

	/**
	 * Save nothing; rendered by server.
	 */
	save() {
		return null;
	},
} );
