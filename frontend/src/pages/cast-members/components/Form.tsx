import React, { BaseSyntheticEvent, ChangeEvent, useCallback, useEffect } from 'react';
import {
    Box,
    Button,
    ButtonProps,
    FormControl,
    FormControlLabel,
    FormLabel,
    makeStyles,
    Radio,
    RadioGroup,
    TextField
} from "@material-ui/core";
import { useForm } from "react-hook-form";
import { CastMember, castMemberHttp, MemberType } from "../../../util/http/cast-member-http";

type FormProps = {}

type FormFields = Omit<CastMember, 'id'>;

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const Form = ({ ..._ }: FormProps) => {
    const classes = useStyles();

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'contained',
        color: 'secondary'
    }

    const { register, handleSubmit, getValues, setValue, watch } = useForm<FormFields>({
        defaultValues: { type: MemberType.Actor }
    });

    const onSubmit = useCallback((formData: FormFields, _: BaseSyntheticEvent | undefined) => {
        castMemberHttp.create(formData)
            .then((response) =>
                console.log(response.data.data)
            )
    }, []);

    const onSubmitOnly = () => onSubmit(getValues(), undefined)

    const onChange = useCallback((e: ChangeEvent<HTMLInputElement>) => {
        setValue('type', +e.target.value)
    }, [ setValue ])

    useEffect(() => {
        register("type")
    }, [ register ]);

    return (
        <form onSubmit={ handleSubmit(onSubmit) }>
            <TextField
                name={ 'name' }
                label={ 'Nome' }
                fullWidth
                variant={ 'outlined' }
                inputProps={ {
                    ...register('name')
                } }
            />

            <FormControl margin={ 'normal' }>
                <FormLabel component={ 'legend' }>Tipo:</FormLabel>
                <RadioGroup
                    name={ 'type' }
                    onChange={ onChange }
                    value={ watch('type', MemberType.Actor) }
                >
                    <FormControlLabel
                        control={ <Radio color={ 'primary' } /> }
                        label={ 'Ator' }
                        value={ MemberType.Actor }
                    />
                    <FormControlLabel
                        control={ <Radio color={ 'primary' } /> }
                        label={ 'Diretor' }
                        value={ MemberType.Director }
                    />
                </RadioGroup>
            </FormControl>

            <Box dir={ 'rtl' }>
                <Button { ...buttonProps } onClick={ onSubmitOnly }>Salvar</Button>
                <Button { ...buttonProps } type={ 'submit' }>Salvar e continuar editando</Button>
            </Box>
        </form>
    )
}

