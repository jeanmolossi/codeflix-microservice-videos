import React from 'react';
import {Box, Button, ButtonProps, makeStyles} from "@material-ui/core";

type SubmitActionsProps = {
    disabled: boolean;
    handleSubmit: () => void;
}

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const SubmitActions = ({disabled, handleSubmit}: SubmitActionsProps) => {
    const classes = useStyles();

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'contained',
        color: 'secondary',
        disabled: disabled ?? false,
    }

    return (
        <Box dir={'rtl'}>
            <Button {...buttonProps} onClick={handleSubmit}>Salvar</Button>
            <Button {...buttonProps} type={'submit'}>Salvar e continuar editando</Button>
        </Box>
    )
}
