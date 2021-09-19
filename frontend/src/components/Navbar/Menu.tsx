import React, { useCallback, useState } from "react";
import { Link } from "react-router-dom";
import { IconButton, MenuItem, Menu as MuiMenu } from "@material-ui/core";
import { Menu as MenuIcon } from "@material-ui/icons";
import { RouteNames, RouteProps, routes } from "../../routes";

const listRoutes: RouteNames[] = [
    'dashboard',
    'categories.list',
    'members.list',
    'genres.list'
];

export const Menu = () => {
    const [ anchorEl, setAnchorEl ] = useState(null);
    const open = Boolean(anchorEl);

    const handleOpen = useCallback((e: any) => setAnchorEl(e.currentTarget), []);

    const handleClose = useCallback(() => setAnchorEl(null), []);

    return (
        <>
            <IconButton
                edge={ 'start' }
                color={ 'inherit' }
                aria-label={ 'open drawer' }
                aria-controls={ 'menu-appbar' }
                aria-haspopup={ "true" }
                onClick={ handleOpen }
            >
                <MenuIcon/>
            </IconButton>

            <MuiMenu
                id={ 'menu-appbar' }
                open={ open }
                anchorEl={ anchorEl }
                anchorOrigin={ { vertical: 'bottom', horizontal: 'center' } }
                transformOrigin={ { vertical: 'top', horizontal: 'center' } }
                getContentAnchorEl={ null }
                onClose={ handleClose }
            >
                {
                    listRoutes.map((routeName, key) => {
                        const route = routes.get(routeName) as RouteProps;

                        return (
                            <MenuItem
                                key={ key }
                                component={ Link }
                                to={ route.path as string }
                                onClick={ handleClose }
                            >
                                { route.label }
                            </MenuItem>
                        );
                    })
                }
            </MuiMenu>
        </>
    );
};
