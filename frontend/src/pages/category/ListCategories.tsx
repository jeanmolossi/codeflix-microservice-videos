import React from 'react';
import { Box, Fab } from "@material-ui/core";
import { Add } from "@material-ui/icons";
import { Page } from "../../components";
import { Link } from "react-router-dom";
import { Table } from "./components";

export const ListCategories = () => {
    return (
        <Page title={ 'Listagem de categorias' }>
            <Box dir={ 'rtl' }>
                <Fab
                    title={ 'Adicionar categoria' }
                    size={ 'small' }
                    component={ Link }
                    to={ '/categorias/criar' }
                    color={ 'secondary' }
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
