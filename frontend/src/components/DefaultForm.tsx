import React, { ReactNode } from 'react';
import { Grid, GridProps, makeStyles } from "@material-ui/core";

type ReactForm = React.DetailedHTMLProps<React.FormHTMLAttributes<HTMLFormElement>, HTMLFormElement>;

interface DefaultFormProps extends ReactForm {
    onSubmit: () => void;
    children?: ReactNode;
    GridContainerProps?: GridProps;
    GridItemProps?: GridProps;
}

const useStyles = makeStyles( theme => ({
    gridItem: {
        padding: theme.spacing( 1, 0 )
    }
}) )

export const DefaultForm = ( { onSubmit, children, GridContainerProps, GridItemProps, ...rest }: DefaultFormProps ) => {
    const classes = useStyles();

    return (
        <form onSubmit={ onSubmit } { ...rest }>
            <Grid container { ...GridContainerProps }>
                <Grid
                    item
                    className={ classes.gridItem }
                    xs={ 12 }
                    md={ 6 }
                    { ...GridItemProps }
                >
                    { children }
                </Grid>
            </Grid>
        </form>
    );
}
