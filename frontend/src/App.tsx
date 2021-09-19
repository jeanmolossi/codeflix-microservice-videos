import React from 'react';
import { Box, CssBaseline, MuiThemeProvider } from "@material-ui/core";
import { Breadcrumbs, Navbar } from "./components";
import { BrowserRouter } from "react-router-dom";
import { AppRouter } from "./routes/AppRouter";
import { theme } from "./config/theme";

function App() {
    return (
        <MuiThemeProvider theme={ theme }>
            <CssBaseline />
            <BrowserRouter>
                <Navbar />
                <Box paddingTop={ '70px' }>
                    <Breadcrumbs />
                    <AppRouter />
                </Box>
            </BrowserRouter>
        </MuiThemeProvider>
    );
}

export default App;
