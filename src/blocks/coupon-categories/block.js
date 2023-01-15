/**
 * External dependencies
 */
const { __ } = wp.i18n;
const { InspectorControls, useBlockProps } = wp.blockEditor;
const { ServerSideRender } = wp.serverSideRender;
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
			"zior-coupon-reveal"
		) }
		className="wc-block-product-categories"
	>
		{ __(
			"This block displays the product categories for your store. To use it you first need to create a product and assign it to a category.",
			"zior-coupon-reveal"
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
		const { hasCount, hasImage, hasEmpty, isDropdown, isHierarchical } =
			attributes;

		return (
			<InspectorControls key="inspector">
				<PanelBody
					title={ __(
						"List Settings",
						"zior-coupon-reveal"
					) }
					initialOpen
				>
					<ToggleGroupControl
						label={ __(
							"Display style",
							"zior-coupon-reveal"
						) }
						value={ isDropdown ? "dropdown" : "list" }
						onChange={ ( value ) =>
							setAttributes( {
								isDropdown: value === "dropdown",
							} )
						}
					>
						<ToggleGroupControlOption
							value="list"
							label={ __(
								"List",
								"zior-coupon-reveal"
							) }
						/>
						<ToggleGroupControlOption
							value="dropdown"
							label={ __(
								"Dropdown",
								"zior-coupon-reveal"
							) }
						/>
					</ToggleGroupControl>
				</PanelBody>
				<PanelBody
					title={ __( "Content", "zior-coupon-reveal" ) }
					initialOpen
				>
					<ToggleControl
						label={ __(
							"Show product count",
							"zior-coupon-reveal"
						) }
						checked={ hasCount }
						onChange={ () =>
							setAttributes( { hasCount: ! hasCount } )
						}
					/>
					{ ! isDropdown && (
						<ToggleControl
							label={ __(
								"Show category images",
								"zior-coupon-reveal"
							) }
							help={
								hasImage
									? __(
											"Category images are visible.",
											"zior-coupon-reveal"
									  )
									: __(
											"Category images are hidden.",
											"zior-coupon-reveal"
									  )
							}
							checked={ hasImage }
							onChange={ () =>
								setAttributes( { hasImage: ! hasImage } )
							}
						/>
					) }
					<ToggleControl
						label={ __(
							"Show hierarchy",
							"zior-coupon-reveal"
						) }
						checked={ isHierarchical }
						onChange={ () =>
							setAttributes( {
								isHierarchical: ! isHierarchical,
							} )
						}
					/>
					<ToggleControl
						label={ __(
							"Show empty categories",
							"zior-coupon-reveal"
						) }
						checked={ hasEmpty }
						onChange={ () =>
							setAttributes( { hasEmpty: ! hasEmpty } )
						}
					/>
				</PanelBody>
			</InspectorControls>
		);
	};

	const blockProps = useBlockProps( {
		className: "wc-block-product-categories",
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
