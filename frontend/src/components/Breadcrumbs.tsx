import React from 'react';
import {
    makeStyles, createStyles,
    Link,
    LinkProps,
    Typography,
    Breadcrumbs as MuiBreadcrumbs, Container, Box,
} from '@material-ui/core';
import { Route } from 'react-router';
import { Link as RouterLink } from 'react-router-dom';
import { Location } from 'history';
import RouteParser from 'route-parser';
import { routes } from "../routes";

const breadcrumbNameMap: { [key: string]: string } = {};

[ ...routes.values() ].forEach(route => {
    breadcrumbNameMap[route.path as string] = route.label
});

const useStyles = makeStyles(() =>
    createStyles({
        root: {
            display: 'flex',
            flexDirection: 'column',
        },
        linkRouter: {
            color: '#4db5ab',
            '&:focus, &:active': { color: '#4db5ab', },
            '&:active': { color: '#4db5ab', },
        }
    }),
);

interface LinkRouterProps extends LinkProps {
    to: string;
    replace?: boolean;
}

const LinkRouter = (props: LinkRouterProps) => <Link { ...props } component={ RouterLink as any } />;

export const Breadcrumbs = () => {
    const makeBreadcrumb = Breadcrumb();

    return (
        <Container>
            <Box paddingBottom={ 2 }>
                <Route>
                    { ({ location }) => makeBreadcrumb(location) }
                </Route>
            </Box>
        </Container>
    );
};

const Breadcrumb = () => {
    const classes = useStyles();

    return (location: Location) => {

        const pathnames = location.pathname
            .split('/')
            .filter((path) => path);

        pathnames.unshift('/')

        return (
            <MuiBreadcrumbs aria-label="breadcrumb">
                { pathnames.map((value, index) => {
                    const last = index === pathnames.length - 1;
                    const to = `${ pathnames.slice(0, index + 1).join('/').replace('//', '/') }`;

                    const route = Object.keys(breadcrumbNameMap)
                        .find(path => new RouteParser(path).match(to))

                    if (!route) {
                        return <></>;
                    }

                    return last ? (
                        <Typography color="textPrimary" key={ to }>
                            { breadcrumbNameMap[route] }
                        </Typography>
                    ) : (
                        <LinkRouter
                            color="inherit"
                            to={ to }
                            key={ to }
                            className={ classes.linkRouter }
                        >
                            { breadcrumbNameMap[route] }
                        </LinkRouter>
                    );
                }) }
            </MuiBreadcrumbs>
        );
    }
}
