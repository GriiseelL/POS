export interface Transaction {
    id: BigInteger;
    id_product: BigInteger;
    quantity: BigInteger;
    price: BigInteger;
    total: BigInteger;
    sub_total: BigInteger;
    permissions?: Array<string>;
}