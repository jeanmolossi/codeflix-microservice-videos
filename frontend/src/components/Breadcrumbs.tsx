import React from 'react';
import {
    Box,
    Breadcrumbs as MuiBreadcrumbs,
    Container,
    createStyles,
    Link,
    LinkProps,
    makeStyles,
    Typography,
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

const useStyles = makeStyles((theme) =>
    createStyles({
        linkRouter: {
            color: theme.palette.secondary.main,
            '&:focus, &:active': {
                color: theme.palette.secondary.main
            },
            '&:hover': {
                color: theme.palette.secondary.dark
            },
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
                        return null;
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
