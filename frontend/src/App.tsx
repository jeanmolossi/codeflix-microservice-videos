import React from 'react';
import { Box, CssBaseline, MuiThemeProvider } from "@material-ui/core";
import { Breadcrumbs, Navbar } from "./components";
import { BrowserRouter } from "react-router-dom";
import { AppRouter } from "./routes/AppRouter";
import { theme } from "./config/theme";
import { SnackbarProvider } from "./components/SnackbarProvider";

function App() {
    return (
        <MuiThemeProvider theme={ theme }>
            <SnackbarProvider>
                <CssBaseline />
                <BrowserRouter>
                    <Navbar />
                    <Box paddingTop={ '70px' }>
                        <Breadcrumbs />
                        <AppRouter />
                    </Box>
                </BrowserRouter>
            </SnackbarProvider>
        </MuiThemeProvider>
    );
}

export default App;
