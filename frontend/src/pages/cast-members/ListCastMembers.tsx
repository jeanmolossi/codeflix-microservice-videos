import React from 'react';
import { Box, Fab } from "@material-ui/core";
import { Add } from "@material-ui/icons";
import { Page } from "../../components";
import { Link } from "react-router-dom";
import { Table } from "./components";


export const ListCastMembers = () => {
    return (
        <Page title={ 'Listagem de membros de elencos' }>
            <Box dir={ 'rtl' }>
                <Fab
                    title={ 'Adicionar membro de elenco' }
                    size={ 'small' }
                    component={ Link }
                    to={ '/membros-elencos/criar' }
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
