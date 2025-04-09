export interface Product {
    id: BigInteger;
    name: string;
    id_category: string;
    price: BigInteger;
    stock: BigInteger;
    permissions?: Array<string>;
}
