import React from 'react';
import {
    AppBar,
    Button,
    makeStyles,
    Theme,
    Toolbar,
    Typography
} from "@material-ui/core";
import { Menu } from './Menu';

import logo from '../../static/logo.png';

const useStyles = makeStyles((theme: Theme) => ({
    toolbar: {
        backgroundColor: `#000000`,
    },
    title: {
        flexGrow: 1,
        textAlign: 'center'
    },
    logo: {
        width: 100,
        [theme.breakpoints.up('sm')]: {
            width: 170
        }
    }
}));

export const Navbar = () => {
    const classes = useStyles();

    return (
        <AppBar>
            <Toolbar className={ classes.toolbar }>
                <Menu />

                <Typography className={ classes.title }>
                    <img src={ logo } alt={ `Codeflix` } className={ classes.logo } />
                </Typography>

                <Button color={ `inherit` }> Login </Button>
            </Toolbar>
        </AppBar>
    );
};
