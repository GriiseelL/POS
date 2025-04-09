import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useCategory(options = {}) {
    return useQuery({
        queryKey: ["categories"],
        queryFn: async () =>
            await axios.get("/product/category").then((res: any) => res.data.data),
        ...options,
    });
}
