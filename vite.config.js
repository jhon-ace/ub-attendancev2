import { defineConfig } from "vite";

import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                
                // "resources/js/bootstrap.js",
            ],
            refresh: true,
        }),

    ],
    server: {
        host: "0.0.0.0", // Allow Vite to be accessed from any IP
        port: 5173, // Ensure the port matches your Vite server configuration
        hmr: {
            // host: "localhost", 
            host: "192.168.33.11", // Adjust HMR host if necessary //
            protocol: 'ws',
        },
        cors: {
            // origin: "*",
            // origin: ["http://192.168.33.11:8085", "http://10.10.5.202:8085", "http://192.168.33.11:8014"],
            origin: [
                "http://192.168.33.11:5173",
                "http://192.168.33.11:8000", //maam monding
                "http://192.168.33.11:8001", //carpark
                "http://192.168.33.11:8002", //diamond
                "http://192.168.33.11:8003", //studentportal-e-security-carpark
                "http://192.168.33.11:8004", // studentportal e-sec-maingate
                "http://192.168.33.11:8005", //esec-student-STBuilding
                "http://192.168.33.11:8006", //eSec-UGS
                "http://192.168.33.11:8007", //VDT-eSec
                "http://192.168.33.11:8008", //dtRMaingate
                "http://192.168.33.11:8009", //dtrST
                "http://192.168.33.11:8010", //dtrGradeSchool
                "http://192.168.33.11:8011", //dtRVDT
                "http://192.168.33.11:8012", // reserve
                "http://192.168.33.11:8013",  //reserve
                "http://192.168.33.11:8014", //reserve
                "http://192.168.33.11:8015", //reserve
            ],
            methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
            allowedHeaders: ["Content-Type", "Authorization"],
            credentials: true,
        },
    },
    //
    base: "/",
});
