import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: "Dashboard",
                name: "dashboard",
                route: "/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },

    // WEBSITE
    {
        heading: "Website",
        route: "/dashboard/website",
        name: "website",
        pages: [
            // MASTER
            {
                sectionTitle: "Master",
                route: "/master",
                keenthemesIcon: "cube-3",
                name: "master",
                sub: [
                    {
                        sectionTitle: "User",
                        route: "/users",
                        name: "master-user",
                        sub: [
                            {
                                heading: "Role",
                                name: "master-role",
                                route: "/dashboard/master/users/roles",
                            },
                            {
                                heading: "User",
                                name: "master-user",
                                route: "/dashboard/master/users",
                            },
                        ],
                    },
                ],
            },
            {
                heading: "Setting",
                route: "/dashboard/setting",
                name: "setting",
                keenthemesIcon: "setting-2",
            },
            {
                sectionTitle: "Product",
                route: "/product",
                keenthemesIcon: "shop",
                name: "product",
                sub: [
                    {
                        heading: "Categories",
                        name: "product-categories",
                        route: "/dashboard/product/categories",
                    },
                    {
                        heading: "Items",
                        route: "/dashboard/product/items",
                        name: "product-items",
                    },
                ],
            },
            {
                heading: "Transaction",
                route: "/dashboard/transaction",
                name: "transaction",
                keenthemesIcon: "bill",
            },
            {
                heading: "Sale",
                route: "/dashboard/sale",
                name: "sale",
                keenthemesIcon: "purchase",
            },
        ],
    },
];

export default MainMenuConfig;
