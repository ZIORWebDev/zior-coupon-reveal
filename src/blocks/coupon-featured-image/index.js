const { assign } = lodash;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

// Enable spacing control on the following blocks
const enableFeaturedImageControlOnBlocks = [
    "core/post-featured-image",
];

/**
 * Add spacing control attribute to block.
 *
 * @param {object} settings Current block settings.
 * @param {string} name Name of block.
 *
 * @returns {object} Modified block settings.
 */
const addFeaturedImageControlAttribute = ( settings, name ) => {
    // Do nothing if it"s another block than our defined ones.
    if ( ! enableFeaturedImageControlOnBlocks.includes( name ) ) {
        return settings;
    }

    // Use Lodash"s assign to gracefully handle if attributes are undefined
    settings.attributes = assign( settings.attributes, {
        pull_featured_image_from_stores: {
            type: "boolean",
            default: false,
        },
        remove_featured_image_on_stores: {
            type: "boolean",
            default: false,
        },
    } );

    return settings;
};

addFilter( "blocks.registerBlockType", "zior/coupon-featured-image", addFeaturedImageControlAttribute );

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, ToggleControl } = wp.components;

/**
 * Create HOC to add spacing control to inspector controls of block.
 */
const withFeaturedImageControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        // Do nothing if it"s another block than our defined ones.
        if ( ! enableFeaturedImageControlOnBlocks.includes( props.name ) ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const { pull_featured_image_from_stores, remove_featured_image_on_stores } = props.attributes;

        return (
            <Fragment>
                <BlockEdit { ...props } />
                <InspectorControls>
                    <PanelBody
                        title={ __( "Manage Featured Image" ) }
                        initialOpen={ true }
                    >
                    <ToggleControl
                        label={__("Pull featured image from store taxonomy if empty")}
                        help={ __(  "If the coupon featured image is empty, get it from store taxonomy.")}
                        checked={pull_featured_image_from_stores}
                        onChange={selected => {
                            props.setAttributes({
                                pull_featured_image_from_stores: selected
                            });
                        }}
                    />
                   <ToggleControl
                        label={__("Hide featured image from store archive")}
                        help={ __(  "Hide featured image from coupons archive page and replace with discount value instead.")}
                        checked={remove_featured_image_on_stores}
                        onChange={selected => {
                            props.setAttributes({
                                remove_featured_image_on_stores: selected
                            });
                        }}
                    />
                    </PanelBody>
                </InspectorControls>
            </Fragment>
        );
    };
}, "withFeaturedImageControl" );

addFilter( "editor.BlockEdit", "zior/coupon-featured-image", withFeaturedImageControl );