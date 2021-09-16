import React from 'react';
import { Box } from "@material-ui/core";
import { Navbar } from "./components";
import { BrowserRouter } from "react-router-dom";
import { AppRouter } from "./routes/AppRouter";
import { Breadcrumbs } from "./components/Breadcrumbs";

function App() {
    return (
        <BrowserRouter>
            <Navbar/>
            <Box paddingTop={ '70px' }>
                <Breadcrumbs/>
                <AppRouter/>
            </Box>
        </BrowserRouter>
    );
}

export default App;
