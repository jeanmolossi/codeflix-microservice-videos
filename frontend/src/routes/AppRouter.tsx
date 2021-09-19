import React from 'react';
import { Route, Switch } from 'react-router-dom';

import { routes } from './index';

export const AppRouter = () => {

    return (
        <Switch>
            { [ ...routes ].map(([ , { path, exact, component } ], key) => (
                <Route
                    key={ key }
                    path={ path }
                    exact={ exact === true }
                    component={ component }
                />
            )) }
        </Switch>
    );
};
