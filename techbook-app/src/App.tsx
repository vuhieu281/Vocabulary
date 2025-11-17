import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import Header from './components/Header';
import Footer from './components/Footer';
import Home from './pages/Home';
import PostDetail from './pages/PostDetail';
import Reviews from './pages/Reviews';
import Discussions from './pages/Discussions';
import Profile from './pages/Profile';
import About from './pages/About';

const App: React.FC = () => {
  return (
    <Router>
      <div>
        <Header />
        <Switch>
          <Route path="/" exact component={Home} />
          <Route path="/post/:id" component={PostDetail} />
          <Route path="/reviews" component={Reviews} />
          <Route path="/discussions" component={Discussions} />
          <Route path="/profile" component={Profile} />
          <Route path="/about" component={About} />
        </Switch>
        <Footer />
      </div>
    </Router>
  );
};

export default App;