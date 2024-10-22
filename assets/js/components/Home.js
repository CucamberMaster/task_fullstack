import React, {Component} from 'react';
import {Route, Redirect, Switch, Link} from 'react-router-dom';
import SetupCheck from './SetupCheck';
import '../../css/Home.css';
import CurrencyRates from './CurrencyRates';

class Home extends Component
{
    render() {
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                    <Link className="navbar-brand" to="#">Telemedi Zadanko</Link>
                    <div className="collapse navbar-collapse">
                        <ul className="navbar-nav">
                            <li className="nav-item">
                                <Link className="nav-link" to="/setup-check">React Setup Check</Link>
                            </li>
                        </ul>
                        <ul className="navbar-nav">
                            <li className="nav-item">
                                <Link className="nav-link" to="/exchange-rates">Kursy Walut</Link>
                            </li>
                        </ul>
                    </div>
                </nav>
                <Switch>
                    <Redirect exact from="/" to="/setup-check"/>
                    <Route path="/setup-check" component={SetupCheck}/>
                    <Route path="/exchange-rates" component={CurrencyRates}/>
                </Switch>
            </div>
        );
    }
}

export default Home;
