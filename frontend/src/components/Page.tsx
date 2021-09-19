import React, { ReactNode } from 'react';
import { Box, Container, makeStyles, Typography } from "@material-ui/core";

type PageProps = {
    children?: ReactNode;
    title: string;
};

const useStyles = makeStyles({
    title: {
        color: '#999'
    }
})

export const Page = ({ title, children }: PageProps) => {
    const classes = useStyles();

    return (
        <Container>
            <Typography
                className={ classes.title }
                component={ 'h1' }
                variant={ 'h5' }
            >
                { title }
            </Typography>

            <Box paddingTop={ 1 }>
                { children }
            </Box>
        </Container>
    );
};
