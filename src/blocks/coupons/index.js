const ZIOR_COUPON_VARIATION = "zior/coupons";
const { registerBlockVariation } = wp.blocks;
registerBlockVariation("core/query", {
    name: ZIOR_COUPON_VARIATION,
    title: "Coupons Loop",
    description: "Displays a list of coupons",
    isActive: ( { namespace, query } ) => {
        return (
            namespace === ZIOR_COUPON_VARIATION
            && query.postType === "coupons"
        );
    },
    icon: "" /** An SVG icon can go here*/,
    attributes: {
        namespace: ZIOR_COUPON_VARIATION,
        query: {
            perPage: 6,
            pages: 0,
            offset: 0,
            postType: "coupons",
            order: "desc",
            orderBy: "date",
            author: "",
            search: "",
            exclude: [],
            sticky: "",
            inherit: false,
        },
    },
    scope: [ "inserter" ],
    innerBlocks: [
        ["core/post-template", {},
            [["core/post-featured-image"], ["core/post-title"]],
        ],
        ["core/query-pagination"],
        ["core/query-no-results"],
    ],
    allowedControls: ["inherit", "order", "taxQuery", "search"],
});