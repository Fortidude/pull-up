import React from 'react';
import { Badge, Button, Icon } from 'antd';
import ExerciseVariantsModal from './ExerciseVariantsModal';

class Single extends React.Component {

    state = {
        variantsModal: false
    }

    render() {
        const { id, name, is_cardio, exercise_variants } = this.props.exercise;
        const length = exercise_variants.length;

        return (
            <div key={id} className="exerciseItem">
                <div>
                    <strong>{name}</strong>
                </div>
                <div>
                    <Button shape="round" type="default" onClick={() => this.setState({ variantsModal: true })}>
                        <span>Warianty</span>
                        <Badge count={length}>
                            <span className="head-example" />
                        </Badge>
                    </Button>
                </div>
                <div>
                    <Button onClick={() => this.props.onEdit(this.props.exercise)} shape="circle" type="primary" icon="form" />
                </div>

                <ExerciseVariantsModal
                    visible={this.state.variantsModal}
                    exerciseVariants={this.props.exercise.exercise_variants}
                    onClose={() => this.setState({ variantsModal: false })}
                />
            </div>
        );
    }
}

export default Single;
