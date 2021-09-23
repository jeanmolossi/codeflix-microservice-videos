import React, { BaseSyntheticEvent, ChangeEvent, useCallback, useEffect, useState } from 'react';
import {
    Box,
    Button,
    ButtonProps,
    FormControl,
    FormControlLabel,
    FormHelperText,
    FormLabel,
    makeStyles,
    Radio,
    RadioGroup,
    TextField
} from "@material-ui/core";
import { useForm } from "react-hook-form";
import { useHistory, useParams } from "react-router-dom";
import { useSnackbar } from "notistack";
import { yupResolver } from '@hookform/resolvers/yup'
import * as yup from 'yup';
import { CastMember, castMemberHttp, MemberType } from "../../../util/http/cast-member-http";

type FormFields = Omit<CastMember, 'id'>;

const validationSchema = yup.object().shape({
    name: yup.string().label("Nome").required(),
    type: yup.number().label("Tipo").required(),
}) as yup.SchemaOf<FormFields>;

const useStyles = makeStyles(theme => ({
    submit: {
        marginRight: theme.spacing(1)
    }
}))

export const Form = () => {
    const classes = useStyles();
    const { id } = useParams<{ id?: string }>();
    const snackbar = useSnackbar();
    const history = useHistory();

    const [ loading, setLoading ] = useState(false);
    const [ castMember, setCastMember ] = useState({} as CastMember);

    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: 'contained',
        color: 'secondary',
        disabled: loading
    }

    const {
        register,
        handleSubmit,
        getValues,
        setValue,
        watch,
        reset,
        formState: { errors }
    } = useForm<FormFields>({
        context: validationSchema,
        resolver: yupResolver(validationSchema),
        defaultValues: { type: MemberType.Actor }
    });

    const saveButtonsBehavior = useCallback((data: CastMember, e?: BaseSyntheticEvent, id?: string) => {
        if (!!e) {
            (!!id
                    ? history.replace(`/membros-elencos/${ data.id }/editar`)
                    : history.push(`/membros-elencos/${ data.id }/editar`)
            )
            return;
        }
        history.push('/membros-elencos')
    }, [ history ])

    const onSubmit = useCallback((formData: FormFields, e: BaseSyntheticEvent | undefined) => {
        const http = castMember.id && id
            ? castMemberHttp.update(id, formData)
            : castMemberHttp.create(formData);

        setLoading(true)
        http
            .then(({ data }) => {
                snackbar.enqueueSnackbar(
                    'Membro de elenco salvo com sucesso',
                    { variant: "success" }
                )

                saveButtonsBehavior(data.data, e, id)
            })
            .catch((err) => {
                snackbar.enqueueSnackbar(
                    err.message,
                    { variant: "error" }
                )
            })
            .finally(() => setLoading(false))
    }, [ id, saveButtonsBehavior, snackbar, castMember.id ]);

    const onSubmitOnly = () => onSubmit(getValues(), undefined)

    const onChange = useCallback((e: ChangeEvent<HTMLInputElement>) => {
        setValue('type', +e.target.value)
    }, [ setValue ])

    useEffect(() => {
        register("type")
    }, [ register ]);

    useEffect(() => {
        if (id) {
            castMemberHttp.get(id)
                .then(({ data }) => {
                    setCastMember(data.data)
                    reset(data.data)
                })
        }
    }, [ id, reset ]);

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
                InputLabelProps={ {
                    shrink: true
                } }
                helperText={ errors.name?.message }
                error={ !!errors.name?.message }
                disabled={ loading }
            />

            <FormControl
                margin={ 'normal' }
                disabled={ loading }
            >
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
                { errors.type?.message &&
                (<FormHelperText error={ !!errors.type?.message }>{ errors.type.message }</FormHelperText>) }
            </FormControl>

            <Box dir={ 'rtl' }>
                <Button { ...buttonProps } onClick={ onSubmitOnly }>Salvar</Button>
                <Button { ...buttonProps } type={ 'submit' }>Salvar e continuar editando</Button>
            </Box>
        </form>
    )
}

