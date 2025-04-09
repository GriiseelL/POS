import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useProduct(options = {}) {
    return useQuery({
        queryKey: ["products"],
        queryFn: async () =>
            await axios.get("/product/items").then((res: any) => res.data.data),
        ...options,
    });
}
