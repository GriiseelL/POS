// //import axios
// import axios from "axios";

// const Api = axios.create({
//   //set default endpoint API
//   baseURL: "http://localhost:8000",
//   headers: {
//     "Content-Type": "application/json",
//     Accept: "application/json",
//   },
// });

// export default Api;

// import axios
import axios from "axios";
// import router from "../router"; // Pastikan ini sesuai path router Anda

const Api = axios.create({
  baseURL: "http://localhost:8000",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor untuk menangani token expired (401 Unauthorized)
// Api.interceptors.response.use(
//   (response) => response,
//   (error) => {
//     if (error.response && error.response.status === 401) {
//       console.warn("Token expired, logout otomatis...");
//       localStorage.removeItem("token");
//       router.push("/login");
//     }
//     return Promise.reject(error);
//   }
// );

export default Api;
