import React from 'react';
import { Button, Modal } from 'antd';
import { withRouter } from 'react-router-dom';

import Exercise from './../../Components/SingleExercise';
import { fetchExercises, createExercise, updateExercise, removeExercise } from '../../api';
import AddExerciseModal from '../../Components/AddExerciseModal';
import UpdateExerciseModal from '../../Components/UpdateExerciseModal';
import ExerciseVariantsModal from '../../Components/ExerciseVariantsModal';

class List extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            exercises: [],
            edit: null,
            variantsModalExercise: null,
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
            const editName = this.state.edit ? this.state.edit.name.toLocaleLowerCase() : null;
            const exerciseName = this.state.variantsModalExercise ? this.state.variantsModalExercise.name.toLocaleLowerCase() : null;
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

            let edit = null;
            let variantsModalExercise = null;
            exercises.forEach((exercise) => {
                if (editName && exercise.name.toLocaleLowerCase() === editName) {
                    edit = exercise;
                }
                if (exerciseName && exercise.name.toLocaleLowerCase() === exerciseName) {
                    variantsModalExercise = exercise;
                }
            })

            this.setState({ exercises, edit, variantsModalExercise });
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

    onVariants = (exercise) => {
        this.setState({ variantsModalExercise: exercise });
    }

    onRemove = (exercise) => {
        removeExercise(exercise.id).then(this.loadList)
    }

    render() {
        return (
            <div className="ExerciseList">
                {this.state.exercises.map(exercise =>
                    <Exercise
                        key={exercise.id}
                        onEdit={this.onEdit}
                        onVariants={this.onVariants}
                        onRemove={this.onRemove}
                        exercise={exercise} />
                )}

                <AddExerciseModal
                    onSuccess={this.handleCreateExercise} />

                <UpdateExerciseModal
                    exercise={this.state.edit}
                    onSuccess={this.handleUpdateExercise}
                    onClose={() => this.setState({ edit: null })} />

                <ExerciseVariantsModal
                    exercise={this.state.variantsModalExercise}
                    onChange={this.loadList}
                    onClose={() => this.setState({ variantsModalExercise: null })} />
            </div>
        )
    }
}

export default withRouter(List);
