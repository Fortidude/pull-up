import React from 'react';
import { Badge, Button, Modal } from 'antd';
import ExerciseVariantsModal from './ExerciseVariantsModal';
import { removeExercise } from '../api';

class Single extends React.Component {

    state = {
        variantsModal: false
    }

    remove = () => {
        const onRemove = () => this.props.onRemove(this.props.exercise);
        Modal.confirm({
            title: 'Are you sure delete this exercise?',
            content: 'All variants will be removed as well.',
            okText: 'Yes',
            okType: 'danger',
            cancelText: 'No',
            onOk() {
                onRemove();
            },
            onCancel() {

            },
        });
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
                    <Button shape="round" type="default" onClick={() => this.props.onVariants(this.props.exercise)}>
                        <span>Warianty</span>
                        <Badge count={length}>
                            <span className="head-example" />
                        </Badge>
                    </Button>
                </div>
                <div>
                    <Button onClick={() => this.props.onEdit(this.props.exercise)} shape="circle" type="primary" icon="form" />
                    <Button onClick={() => this.remove()} shape="circle" type="danger" icon="delete" />
                </div>
            </div>
        );
    }
}

export default Single;
