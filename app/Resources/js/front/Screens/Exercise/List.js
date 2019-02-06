import React from 'react';
import { Button, Modal } from 'antd';
import { withRouter } from 'react-router-dom';

import Exercise from './../../Components/SingleExercise';
import { fetchExercises, createExercise, updateExercise } from '../../api';
import AddExerciseModal from '../../Components/AddExerciseModal';
import UpdateExerciseModal from '../../Components/UpdateExerciseModal';

class List extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            exercises: [],
            edit: null,
            lastCreatedName: ""
        }
    }

    componentDidMount() {
        this.loadList();
    }

    /**
     * LOAD LIST
     */
    loadList = () => {
        fetchExercises().then(exercises => {
            exercises.sort((a, b) => {
                const nameA = a.name.toUpperCase();
                const nameB = b.name.toUpperCase();

                if (nameA == this.state.lastCreatedName) {
                    return -1;
                }

                if (nameB == this.state.lastCreatedName) {
                    return 1;
                }

                return nameA > nameB;
            });

            this.setState({ exercises });
        })
            .catch(error => {
                if (error.toString() == "Error: Unauthorized") {
                    window.localStorage.removeItem('_t');
                    window.localStorage.removeItem('user');
                    this.props.history.replace('/login');
                }
            })
    }

    /**
     * CREATE EXERCISE
     */
    handleCreateExercise = (name, isCardio) => {
        createExercise(name, isCardio).then(response => {
            this.setState({ lastCreatedName: name.toUpperCase() });
            this.loadList();
        })
    }

    handleUpdateExercise = (id, name, description, isCardio) => {
        updateExercise(id, name, description, isCardio).then(response => {
            this.setState({ edit: null });
            this.loadList();
        })
    }

    onEdit = (exercise) => {
        this.setState({ edit: exercise });
    }

    render() {
        return (
            <div className="ExerciseList">
                {this.state.exercises.map(exercise =>
                    <Exercise
                        key={exercise.id}
                        onEdit={this.onEdit}
                        exercise={exercise} />
                )}

                <AddExerciseModal
                    onSuccess={this.handleCreateExercise} />

                <UpdateExerciseModal
                    exercise={this.state.edit}
                    onSuccess={this.handleUpdateExercise} />
            </div>
        )
    }
}

export default withRouter(List);
