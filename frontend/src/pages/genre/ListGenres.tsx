import React from 'react';
import { Box, Fab } from "@material-ui/core";
import { Add } from "@material-ui/icons";
import { Page } from "../../components";
import { Link } from "react-router-dom";
import { Table } from "./components";

export const ListGenres = () => {
    return (
        <Page title={ 'Listagem de generos' }>
            <Box dir={ 'rtl' }>
                <Fab
                    title={ 'Adicionar genero' }
                    size={ 'small' }
                    component={ Link }
                    to={ '/generos/criar' }
                >
                    <Add />
                </Fab>
            </Box>
            <Box>
                <Table />
            </Box>
        </Page>
    );
};
