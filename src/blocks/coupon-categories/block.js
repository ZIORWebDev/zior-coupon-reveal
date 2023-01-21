/**
 * External dependencies
 */
const { __ } = wp.i18n;
const { InspectorControls, useBlockProps } = wp.blockEditor;
const { serverSideRender: ServerSideRender } = wp;

import PropTypes from "prop-types";
import { Icon, listView } from "@wordpress/icons";
const {
	Disabled,
	PanelBody,
	ToggleControl,
	Placeholder,
} = wp.components;
const {
	ToggleGroupControl
} = wp.components.__experimentalToggleGroupControl;
const {
	ToggleGroupControlOption,
} = wp.components.__experimentalToggleGroupControlOption;

const EmptyPlaceholder = () => (
	<Placeholder
		icon={ <Icon icon={ listView } /> }
		label={ __(
			"Coupons Categories List",
			"zior-couponreveal"
		) }
		className="zior-block-coupon-categories"
	>
		{ __(
			"This block displays the coupon categories for your store. To use it you first need to create a coupon and assign it to a category.",
			"zior-couponreveal"
		) }
	</Placeholder>
);

/**
 * Component displaying the categories as dropdown or list.
 *
 * @param {Object}            props               Incoming props for the component.
 * @param {Object}            props.attributes    Incoming block attributes.
 * @param {function(any):any} props.setAttributes Setter for block attributes.
 * @param {string}            props.name          Name for block.
 */
const CouponCategoriesBlock = ( { attributes, setAttributes, name } ) => {
	const getInspectorControls = () => {
		const { hasCount, hasImage, isHierarchical } =
			attributes;

		return (
			<InspectorControls key="inspector">
				<PanelBody
					title={ __(
						"List Settings",
						"zior-couponreveal"
					) }
					initialOpen
				>
				</PanelBody>
				<PanelBody
					title={ __( "Content", "zior-couponreveal" ) }
					initialOpen
				>
					<ToggleControl
						label={ __(
							"Show coupon count",
							"zior-couponreveal"
						) }
						checked={ hasCount }
						onChange={ () =>
							setAttributes( { hasCount: ! hasCount } )
						}
					/>
					<ToggleControl
						label={ __(
							"Show category images",
							"zior-couponreveal"
						) }
						help={
							hasImage
								? __(
										"Category images are visible.",
										"zior-couponreveal"
									)
								: __(
										"Category images are hidden.",
										"zior-couponreveal"
									)
						}
						checked={ hasImage }
						onChange={ () =>
							setAttributes( { hasImage: ! hasImage } )
						}
					/>
					<ToggleControl
						label={ __(
							"Show hierarchy",
							"zior-couponreveal"
						) }
						checked={ isHierarchical }
						onChange={ () =>
							setAttributes( {
								isHierarchical: ! isHierarchical,
							} )
						}
					/>
				</PanelBody>
			</InspectorControls>
		);
	};

	const blockProps = useBlockProps( {
		className: "zior-block-coupon-categories",
	} );

	return (
		<div { ...blockProps }>
			{ getInspectorControls() }
			<Disabled>
				<ServerSideRender
					block={ name }
					attributes={ attributes }
					EmptyResponsePlaceholder={ EmptyPlaceholder }
				/>
			</Disabled>
		</div>
	);
};

CouponCategoriesBlock.propTypes = {
	/**
	 * The attributes for this block
	 */
	attributes: PropTypes.object.isRequired,
	/**
	 * The register block name.
	 */
	name: PropTypes.string.isRequired,
	/**
	 * A callback to update attributes
	 */
	setAttributes: PropTypes.func.isRequired,
};

export default CouponCategoriesBlock;
